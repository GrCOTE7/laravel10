<?php

namespace App\Http\Controllers;

use App\Http\classes\ExportManager;
use App\Http\classes\IaManager;
use App\Http\Tools\Gc7;

class AdController extends Controller
{
	protected $aff = 1;

	// protected $exports;

	protected $fileN = 2; // @i Choix numéro de fichier

	protected $adN = 1; // @i Choix numéro de l'annonce dans la liste

	protected $file;

	// protected $adForIa;

	protected $askAi = 0; // @i Choix mode IA - 0 simu - 1 réel

	protected $keys;

	protected $error;

	public function __construct()
	{
		ini_set('max_execution_time', '0');
		date_default_timezone_set('Europe/Paris');
		// $this->ads = (new ExportManager())->getAds();
		$this->file = (new ExportManager())->exports;

		// $this->file->adForIa = $this->file->ads[$this->file->adForIaId];

		// Gc7::affH($this->file->adForIa); // The Ad for IA
		// Gc7::aff($this->file->ads); // The ads
	}

	public function index()
	{
		// Gc7::affH($this->file->ads[$this->file->adForIaId]);
		// $str = $this->allAdsWithFields();

		// $this->adForIa = $this->setAdForIa();
		$adIa = (new IaManager())->index();

		$this->affAds($adIa);

		// Gc7::aff($adIa);

		return $this->error ?? 'no';
	}

	// @i Called by /test
	public function test()
	{
		$this->keys = (new IaManager())->index();
		echo '<hr>';

		$ad = $this->file->ads[$this->adN];

		$property = new \stdClass();

		foreach ($this->keys as $k => $key) {
			$property->{$k} = $ad[$key] ?? null;
			echo $k . ': ' . $property->{$k} . '<br><br>';
		}

		Gc7::affH($this->file->ads[$this->adN]);

		// phpinfo();
		// $this->exports = (new ExportManager())->exportFilesToHtmlTable();

		// $this->exports->exportFilesToHtmlTable();

		// Gc7::aff($this->exports);
		// Gc7::aff($this->exports->selectedFile);

		// $this->ads = $ads = $this->exports->ads[$this->fileN]['ads'];
		// Gc7::aff($this->fileN);
		// Gc7::aff($this->ads->exports->files[$this->fileN]['ads'][$this->adN]);
		// Gc7::aff($this, '***');

		// $newFields = (new IaManager())->getFields($ads);

		// Gc7::aff($newFields, '$newFields');

		// return $this->ads->exports->selectedFile->name;
		// return 'Fichier #' . $this->fileN . ' (' . $this->ads->exports->selectedFile->name . ') - ' . count($this->ads->exports->selectedFile->ads) . ' ads.';
		// var_dump($this->ads->exports);

		if ($this->askAi) {
			exit(Gc7::aff($this->keys));
		}
		Gc7::aff($this->keys, '$keys From Ia');

		return 'Fichier #' . $this->fileN;
	}

	protected function affAds($adIa): void
	{
		// Gc7::aff($adIa);
		$ads  = $this->file->ads;
		$html = '<div class="container" style="font-family: arial;"><h3>Fichier: ' . $this->fileN . ' (' . $this->file->name . ' - ' . $this->file->adsCount . ' ads)</h3>
        <table class="table table-sm table-bordered table-rounded m-auto" style="width: 97%">
        <tr style="text-align: center">';

		$html .= '<th>Id</th>';
		$i = 0;
		foreach ($adIa->keys as $k => $field) {
			if (1 != $i++) {
				$html .= '<th>' . $k . '<br>' . $field . '</th>';
			}
		}
		foreach ($ads as $k => $ad) {
			$html .= '</tr><tr style="text-align: center"><td>' . $k . '</td>';

			$i = 0;
			foreach ($adIa->keys as $field) {
				if (0 == $i && empty($ad[$field])) {
                    $field = $adIa->keys->fallback_property_location;
				}

				// if (empty($ad[$field])) {
				// 	$ad[$field] = '<h1>XXXXX</h1><hr>' . $ad['textcaption'];
				// }
				if (1 != $i++) {
					$html .= '<td>' . $ad[$field] . '</td>';
				}
			}
		}

		$html .= '</tr>';
		// foreach ($files as $k => $file) {
		// 	$bgcolor = $file['bgColor'];

		// 	$fileDetails = $this->fileDetails($this->folder . $file['name']);

		// 	// Gc7::aff($file);

		// 	$html .= '<tr><td style="text-align: right;background-color:' . $bgcolor . ';">' . $k . '</td>
		//     <td style="background-color:' . $bgcolor . '">' . $file['name'] . '</td>
		//     <td style="text-align: right;background-color:' . $bgcolor . '">' . $file['adsCount'] . '</td>
		//     <td style="text-align: right;background-color:' . $bgcolor . '">' . $fileDetails->fieldsCount . '</td>
		//     <td style="text-align: right;background-color:' . $bgcolor . '">' . $fileDetails->adIdTopFieldsCount . '</td>
		//     <td style="text-align: center;background-color:' . $bgcolor . '">' . date('d/m/Y à H:i:s', $file['createdAt']) . '</td></tr>';
		// }
		$html .= '</table></div>';

		echo $html . '<hr>';
	}

	protected function setAdForIa()
	{
		$adForIa       = new \stdClass();
		$adForIa->id   = $this->file->adForIaId;
		$adForIa->ad   = $this->file->ads[$this->file->adForIaId];
		$adForIa->keys = array_keys($adForIa->ad, true);
		// echo count($adForIa->keys);
		$cutField           = array_search(array_search('Critères', $adForIa->ad), $adForIa->keys);
		$adForIa->cut       = array_slice($adForIa->ad, 0, $cutField);
		$adForIa->forFilter = array_slice($adForIa->ad, $cutField + 1);

		// Gc7::affH($adForIaCut);
		// Gc7::affH($adForFilter);
		return $adForIa;
	}

	protected function allAdsWithFields()
	{
		$ads         = $this->file->ads;
		$adCount     = count($ads);
		$fieldsCount = count($ads[$this->file->adForIaId]);
		for ($i = 0; $i < $fieldsCount; ++$i) {
			foreach ($ads as $k => $ad) {
				$adN = array_values($ad);
				$adK = array_keys($ad);

				echo $k . ' : ' . $adK[$i] . ' : ' . $adN[$i] . '<br>';
			}
			echo '<hr>';
		}

		return $adCount . ' - ' . $fieldsCount;
	}
}
