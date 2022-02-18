<?php
namespace PFBC\Element;

class Date extends Textbox {
    protected $_attributes = array(
        "type" => "date"
    );

    public function __construct($label, $name, array $properties = null) {
        $this->_attributes["title"] = "DD/MM/YYYY (e.g. " . date("d/m/Y") . ")";

        parent::__construct($label, $name, $properties);
    }

    public function render() {
        $this->validation[] = new \PFBC\Validation\RegExp("/" . $this->_attributes["pattern"] . "/", "Error: The %element% field must match the following date format: " . $this->_attributes["title"]);
        parent::render();
    }
}
