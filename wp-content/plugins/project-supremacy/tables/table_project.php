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

class GKTY_Table_Project extends WP_List_Table
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
   * Table constructor.
   * @return INV_Table_Account
   */
  function __construct()
  {
    $this->per_page = 25;

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
      'name' => __('Project Name', 'gkty_lang'),
      'created_date' => __('Created Date', 'gkty_lang'),
      'export_action' => __('Export', 'gkty_lang'),
    );

    return $columns;
  }

  /**
   * Retrieves contest data.
   */
  function get_data()
  {
    $orderby = 'name';
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

    $data = GKTY_Model_Project::getAll($orderby, $order, $this->get_pagenum(), $this->per_page);
    $this->found_posts = GKTY_Model_Project::$foundCount;
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
      'created_date' => array('created_date', false),
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
        $output = '<a href="' . add_query_arg(array('page' => GKTY_Page_Project::PAGE_ID, 'ch_page' => 'edit_project', 'p_id' => $item['id']), admin_url('admin.php')) . '">' . $item['name'] . '</a>';
        $actions = array();

        $actions['edit'] = '<a href="' . add_query_arg(array('page' => GKTY_Page_Project::PAGE_ID, 'ch_page' => 'edit_project', 'p_id' => $item['id']), admin_url('admin.php')) . '">' . __('Open', 'gkty_lang') . '</a>';
        $actions['trash'] = '<a href="' . add_query_arg(array('page' => GKTY_Page_Project::PAGE_ID, 'action' => 'trash_p', 'p_id' => $item['id']), admin_url('admin.php')) . '">' . __('Trash', 'gkty_lang') . '</a>';

        $output .= $this->row_actions($actions);
        break;
      case 'id':
        $output = '#' . intval($item['id']);
        break;
      case 'created_date':
        $time_zone = intval(get_option('gmt_offset'));
        $created_date = strtotime($item['created_date']) + $time_zone * 60 * 60;
        $output = date('Y-m-d', $created_date);
        break;
      case 'export_action':
        $output = '<a href="' . add_query_arg(array('page' => GKTY_Page_Project::PAGE_ID, 'action' => 'gkty_export_project', 'p_id' => $item['id']), admin_url('admin.php')) . '">' . __('Export to CSV', 'gkty_lang') . '</a>';
        break;
      default:
        $output = '';
        break;
    }
    return $output;
  }

  function column_cb($item)
  {
    return sprintf('<input type="checkbox" name="projects[]" value="%s" />', $item['id']);
  }

  function get_bulk_actions()
  {

    $actions = array(
      'trash_p' => __('Trash', 'gkty_lang')
    );

    return $actions;
  }
}
