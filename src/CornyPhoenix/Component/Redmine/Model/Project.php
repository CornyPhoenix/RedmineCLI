<?php

namespace CornyPhoenix\Component\Redmine\Model;

use Carbon\Carbon;

class Project
{


    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $identifier;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $homepage;

    /**
     * @var
     */
    private $parent;

    /**
     * @var int
     */
    private $status;

    /**
     * @var boolean
     */
    private $public;

    /**
     * @var array
     */
    private $custom_fields;

    /**
     * @var array
     */
    private $trackers;

    /**
     * @var array
     */
    private $issue_categories;

    /**
     * @var Carbon
     */
    private $created_on;

    /**
     * @var Carbon
     */
    private $updated_on;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     * @return $this
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getHomepage()
    {
        return $this->homepage;
    }

    /**
     * @param string $homepage
     * @return $this
     */
    public function setHomepage($homepage)
    {
        $this->homepage = $homepage;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     * @return $this
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isPublic()
    {
        return $this->public;
    }

    /**
     * @param boolean $public
     * @return $this
     */
    public function setPublic($public)
    {
        $this->public = $public;
        return $this;
    }

    /**
     * @return array
     */
    public function getCustomFields()
    {
        return $this->custom_fields;
    }

    /**
     * @param array $custom_fields
     * @return $this
     */
    public function setCustomFields(array $custom_fields)
    {
        $this->custom_fields = $custom_fields;
        return $this;
    }

    /**
     * @return array
     */
    public function getTrackers()
    {
        return $this->trackers;
    }

    /**
     * @param array $trackers
     * @return $this
     */
    public function setTrackers(array $trackers)
    {
        $this->trackers = $trackers;
        return $this;
    }

    /**
     * @return array
     */
    public function getIssueCategories()
    {
        return $this->issue_categories;
    }

    /**
     * @param array $issue_categories
     * @return $this
     */
    public function setIssueCategories(array $issue_categories)
    {
        $this->issue_categories = $issue_categories;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getCreatedOn()
    {
        return $this->created_on;
    }

    /**
     * @param Carbon $created_on
     * @return $this
     */
    public function setCreatedOn(Carbon $created_on)
    {
        $this->created_on = $created_on;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getUpdatedOn()
    {
        return $this->updated_on;
    }

    /**
     * @param Carbon $updated_on
     * @return $this
     */
    public function setUpdatedOn(Carbon $updated_on)
    {
        $this->updated_on = $updated_on;
        return $this;
    }
}