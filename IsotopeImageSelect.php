<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Isotope eCommerce Workgroup 2009-2012
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


class IsotopeImageSelect extends Frontend
{

	public function mergeData($strField, $arrData, &$objProduct=null)
	{
		$size = deserialize($arrData['attributes']['imgSize']);

		$images = array();
		$auxDate = array();

		// Get all images
		$arrFiles = scan(TL_ROOT . '/' . $arrData['attributes']['imageSource']);
		$this->parseMetaFile($arrData['attributes']['imageSource']);

		// Folders
		foreach ($arrFiles as $file)
		{
			if (is_dir(TL_ROOT . '/' . $arrData['attributes']['imageSource'] . '/' . $file))
			{
				continue;
			}

			$objFile = new File($arrData['attributes']['imageSource'] . '/' . $file);

			if ($objFile->isGdImage)
			{
				$arrMeta = $this->arrMeta[$file];

				if ($arrMeta[0] == '')
				{
					$arrMeta[0] = str_replace('_', ' ', preg_replace('/^[0-9]+_/', '', $objFile->filename));
				}

				$images[$file] = array
				(
					'name' => $objFile->basename,
					'singleSRC' => $this->getImage($arrData['attributes']['imageSource'] . '/' . $file, $size[0], $size[1], $size[2]),
					'alt' => $arrMeta[0],
					'imageUrl' => $arrMeta[1],
					'caption' => $arrMeta[2]
				);

				$auxDate[] = $objFile->mtime;
			}
		}

		// Sort array
		switch ($arrData['attributes']['sortBy'])
		{
			default:
			case 'name_asc':
				uksort($images, 'basename_natcasecmp');
				break;

			case 'name_desc':
				uksort($images, 'basename_natcasercmp');
				break;

			case 'date_asc':
				array_multisort($images, SORT_NUMERIC, $auxDate, SORT_ASC);
				break;

			case 'date_desc':
				array_multisort($images, SORT_NUMERIC, $auxDate, SORT_DESC);
				break;

			case 'meta':
				$arrImages = array();
				foreach ($this->arrAux as $k)
				{
					if (strlen($k))
					{
						$arrImages[] = $images[$k];
					}
				}
				$images = $arrImages;
				break;
		}

		$arrData['images'] = $images;
		$arrData['eval']['includeBlankOption'] = false;

		return $arrData;
	}


}

