<?php


class TableHeader extends Component
{

    private $columnName;

    public function __construct(Column $field)
    {
        parent::__construct(file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "admin" . DIRECTORY_SEPARATOR . "TableHeader.xhtml"));
        $this->columnName = $field->columnDescription();
    }

    public function build()
    {
        return str_replace("{columnName}", $this->columnName, $this->baseLayout());
    }
}