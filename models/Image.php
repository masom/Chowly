<?php
namespace chowly\models;


class Image extends \lithium\data\Model{
	protected $_meta = array('source' => 'images.files');
	protected function write() {

			$this->fileName = $this->request->data['Filedata']['name'];
			$md5 = md5_file($this->request->data['Filedata']['tmp_name']);
			$file = File::first(array('conditions' => array('md5' => $md5)));
			if ($file) {
				$success = true;
				$this->id = (string) $file->_id;
			} else {
				$this->id = (string) $grid->storeUpload('Filedata', $this->fileName);
				if ($this->id) {
					$success = true;
				}
			}
			return $success;
	}
}