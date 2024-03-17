<?php
use engagewp\EngageFormsQuery\Features\FeatureContainer;
use \engagewp\EngageFormsQuery\Select\EntryValues as EntryValueSelect;

/**
 * Interface Engage_Forms_Query_Paginates
 *
 * A common interface for classes that perform paginated queries using EngageFormsQueries FeatureContainer
 */
interface Engage_Forms_Query_Paginates
{

    /**
     * Get the page property
     *
     * @since 1.7.0
     *
     * @return int
     */
    public function get_page();

    /**
     * Get the limit/ per page property
     *
     * @since 1.7.0
     *
     * @return int
     */
    public function get_limit();

    /**
     * Set the page property
     *
     * @since 1.7.0
     *
     * @param int $page
     * @return $this
     */
    public function set_page($page);


    /**
     * (re)Set the limit/ per page property
     *
     * @since 1.7.0
     *
     * @param int $limit
     * @return $this
     */
    public function set_limit($limit);


    /**
     * Get EngageFormsQueries FeatureContainer
     *
     * @since 1.7.0
     *
     * @return FeatureContainer
     */
    public function get_queries_container();


    /**
     * Query for entry values from entries of this form
     *
     * @since 1.7.0
     *
     * @param EntryValueSelect $entry_value_select
     * @return Engage_Forms_Entry_Fields
     */
    public function select_values_for_form( EntryValueSelect $entry_value_select );
}