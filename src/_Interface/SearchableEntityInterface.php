<?php

namespace App\_Interface;

/**
 * Interface that enables automatic global search for entities
 */
interface SearchableEntityInterface
{
    /**
     * Returns default field to search by if no field is given by the user
     * Field should be a valid field name in the following entity class
     * @return string
     */
    public static function getDefaultSearchFieldName(): string;

    /**
     * Search Card title
     * @return string
     */
    public function getSearchCardTitle(): string;

    /**
     * Search card body or description
     * @return string
     */
    public function getSearchCardBody(): string;

    /**
     * Search Card's image if there is one
     * @return ?string
     */
    public function getSearchCardImage(): ?string;
}