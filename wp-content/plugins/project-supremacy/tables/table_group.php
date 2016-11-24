<?php

/**
 * Admin dashboard table : colors table
 * @package FCP_Table
 */

if(!class_exists('WP_List_Table'))
  require_once(ABSPATH.'wp-admin/includes/class-wp-list-table.php');

/**
 * Table class that handles loading and displaying WordPress list table for contests.
 * @package GKTY_Table
 */
/**
 * Table class that handles loading and displaying WordPress list table for contests.
 * @package GKTY_Table
 */

class GKTY_Table_Group extends WP_List_Table
{
  /**
   * Number of all contests.
   * @var int
   */
  private $found_posts;

  /**
   * Number of contests per page.
   * @var int
   */
  private $per_page;

  /**
   * Project ID
   */
  private $projectID;

  /**
   * Table constructor.
   */
  function __construct($project_id)
  {
    $this->per_page = 25;
    $this->projectID = $project_id;

    parent::__construct();
  }

  /**
   * Sets table columns.
   * @return mixed
   */
  function get_columns()
  {
    $columns = array(
      'cb' => '<input type="checkbox" >',
      'id' => __('UID', 'gkty_lang'),
      'name' => __('Group Name', 'gkty_lang'),
      'title' => __('Title', 'gkty_lang'),
      'url' => __('URL', 'gkty_lang'),
      'description' => __('Description', 'gkty_lang'),
      'h1' => __('H1', 'gkty_lang'),
      //'keywords' => __('Keywords', 'gkty_lang'),
    );

    return $columns;
  }

  /**
   * Retrieves contest data.
   */
  function get_data()
  {
    $orderby = 'id';
    $order = 'asc';

    if(isset($_GET['orderby'])){
      $param = $_GET['orderby'];
      if($param == 'name') {
        $orderby = 'name';
      } else {
        $orderby = 'name';
      }
    }

    if(isset($_GET['order']))
      $order = $_GET['order'];

    $data = GKTY_Model_Group::getAll($this->projectID, $orderby, $order, $this->get_pagenum(), $this->per_page);
    $this->found_posts = GKTY_Model_Group::$foundCount;
    return $data;
  }

  /**
   * Sets sortable table columns.
   */
  function get_sortable_columns()
  {
    $sortable_columns = array(
      'id' => array('id', false),
      'name' => array('name', false),
    );
    return $sortable_columns;
  }

  /**
   * Initializes table data.
   */
  function prepare_items()
  {
    $columns = $this->get_columns();
    $sortable = $this->get_sortable_columns();
    $this->_column_headers = array($columns, array(), $sortable);

    $this->items = $this->get_data();

    $this->set_pagination_args( array(
      'total_items' => $this->found_posts,
      'per_page'    => $this->per_page
    ));
  }

  /**
   * Generates columns.
   *
   * @param CH_Contest $item Current contest.
   * @param string $column_name Current column text ID.
   */
  function column_default($item, $column_name)
  {
    switch($column_name)
    {
      case 'name':
        $output = '<a href="' . add_query_arg(array('ch_page1' => 'edit_group', 'g_id' => $item['id'])) . '">' . $item['name'] . '</a>';
        $actions = array();

        $actions['edit'] = '<a href="' . add_query_arg(array('ch_page1' => 'edit_group', 'g_id' => $item['id'])) . '">' . __('Edit', 'gkty_lang') . '</a>';
        $actions['trash'] = '<a href="' . add_query_arg(array('action' => 'trash_g', 'g_id' => $item['id'])) . '">' . __('Trash', 'gkty_lang') . '</a>';

        $output .= $this->row_actions($actions);
        break;
      case 'id':
        $output = '#' . intval($item['id']);
        break;
      case 'title':
        $output = $item['title'];
        break;
      case 'url':
        $output = $item['url'];
        break;
      case 'description':
        $output = $item['description'];
        break;
      case 'h1':
        $output = $item['h1'];
        break;
      default:
        $output = '';
        break;
    }
    return $output;
  }

  function column_cb($item)
  {
    return sprintf('<input type="checkbox" name="groups[]" value="%s" />', $item['id']);
  }

  function get_bulk_actions()
  {

    $actions = array(
      'trash_g' => __('Trash', 'gkty_lang')
    );

    return $actions;
  }
}
