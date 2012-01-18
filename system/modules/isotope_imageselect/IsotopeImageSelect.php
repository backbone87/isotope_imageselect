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
		$this->parseFiles($arrFiles, deserialize($arrData['attributes']['imageSource'], true), $arrData['attributes']['imageselect_recursive']);
		$arrImages = $this->compileImages($arrFiles, deserialize($arrData['attributes']['imgSize']), $arrAuxDate);
		
		// Sort array
		switch ($arrData['attributes']['sortBy'])
		{
			default:
			case 'name_asc':
				uksort($arrImages, 'basename_natcasecmp');
				break;

			case 'name_desc':
				uksort($arrImages, 'basename_natcasercmp');
				break;

			case 'date_asc':
				array_multisort($arrImages, SORT_NUMERIC, $arrAuxDate, SORT_ASC);
				break;

			case 'date_desc':
				array_multisort($arrImages, SORT_NUMERIC, $arrAuxDate, SORT_DESC);
				break;

			case 'meta':
				$arrSorted = array();
				foreach ($this->arrAux as $k)
				{
					if (strlen($k))
					{
						$arrSorted[] = $arrImages[$k];
					}
				}
				$arrImages = $arrSorted;
				break;
		}
		
		unset($arrData['options'], $arrData['reference']);

		foreach($arrImages as $strFile => $arrImage) {
			$arrData['options'][] = $strFile;
			$arrData['reference'][$strFile] = $arrImage['alt'];
		}
		
		$arrData['images'] = $arrImages;
		$arrData['eval']['includeBlankOption'] = false;

		return $arrData;
	}
	
	
	
	protected function parseFiles(&$arrFiles, $arrSources, $blnRecursive = true) {
		foreach($arrSources as $strSource) {
			$strPath = TL_ROOT . '/' . $strSource;
			if(is_file($strPath)) {
				$arrFiles[] = $strSource;
			} elseif(is_dir($strPath)) {
				$this->parseDir($arrFiles, $strSource . '/', $blnRecursive);
			}
		}
	}
	
	protected function parseDir(&$arrFiles, $strDir, $blnRecursive = true) {
		foreach(scan(TL_ROOT . '/' . $strDir) as $strFile) {
			if($strFile[0] == '.' || (strncasecmp($strFile, 'meta', 4) === 0 && ($strFile[4] == '.' || $strFile[4] == '_')))
				continue;
			$strFile = $strDir . $strFile;
			$strPath = TL_ROOT . '/' . $strFile;
			if(is_file($strPath)) {
				$arrFiles[] = $strFile;
			} elseif($blnRecursive && is_dir($strPath)) {
				$this->parseDir($arrFiles, $strFile . '/');
			}
		}
	}
	
	protected function compileImages($arrFiles, $arrSize, &$arrAuxDate) {
		$arrAuxDate = array();
		
		foreach($arrFiles as $strFile) {
			$objFile = new File($strFile);

			if(!$objFile->isGdImage) {
				continue;
			}
			
			$this->parseMetaFile(dirname($strFile));
			$arrMeta = $this->arrMeta[$objFile->basename];
			
			if ($arrMeta[0] == '')
			{
				$arrMeta[0] = str_replace('_', ' ', preg_replace('/^[0-9]+_/', '', $objFile->filename));
			}

			$arrImages[$strFile] = array(
				'name' => $objFile->basename,
				'singleSRC' => $this->getImage($strFile, $arrSize[0], $arrSize[1], $arrSize[2]),
				'alt' => $arrMeta[0],
				'imageUrl' => $arrMeta[1],
				'caption' => $arrMeta[2]
			);

			$arrAuxDate[] = $objFile->mtime;
		}
		
		return $arrImages;
	}

}
