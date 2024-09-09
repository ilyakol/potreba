<?php
namespace Potreba\App\classes;

require_once 'Ad.php'; 

class JobAd extends Ad
{
    protected $salary;

    public function __construct($title, $text, $category, $image, $salary)
    {
        parent::__construct($title, $text, $category, $image);
        $this->salary = $salary;
    }

    public function getDetails()
{
    return parent::getDetails() . ' -ЗП: ' . $this->salary;
}

}
