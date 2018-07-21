<?php
use CRM_Paymentreport_ExtensionUtil as E;

class CRM_Paymentreport_Form_Report_PaymentReport extends CRM_Report_Form {

  protected $_addressField = FALSE;

  protected $_emailField = FALSE;

  protected $_summary = NULL;

  protected $_customGroupExtends = array('Contribution');
  protected $_customGroupGroupBy = FALSE; function __construct() {
    $this->_columns = array(
      'civicrm_contact' => array(
        'dao' => 'CRM_Contact_DAO_Contact',
        'fields' => array(
          'sort_name' => array(
            'title' => E::ts('Contact Name'),
            'required' => TRUE,
            'default' => TRUE,
            'no_repeat' => FALSE,
          ),
          'id' => array(
            'no_display' => TRUE,
            'required' => TRUE,
          ),
          'first_name' => array(
            'title' => E::ts('First Name'),
            'no_repeat' => TRUE,
            'no_display' => TRUE,
          ),
          'id' => array(
            'no_display' => TRUE,
            'required' => TRUE,
          ),
          'last_name' => array(
            'title' => E::ts('Last Name'),
            'no_repeat' => TRUE,
            'no_display' => TRUE,
          ),
          'id' => array(
            'no_display' => TRUE,
            'required' => TRUE,
          ),
        ),
        'filters' => array(
          'sort_name' => array(
            'title' => E::ts('Contact Name'),
            'operator' => 'like',
          ),
          'id' => array(
            'no_display' => TRUE,
          ),
        ),
        'order_bys' => array(
          'sort_name' => array('title' => E::ts('Contact Name')),
        ),
        'grouping' => 'contact-fields',
      ),
      'civicrm_entity_financial_trxn' => array(
        'dao' => 'CRM_Financial_DAO_EntityFinancialTrxn',
        'fields' => array(
          'amount' => array(
            'title' => E::ts('Payment Amount'),
            'default' => TRUE,
            'type' => CRM_Utils_Type::T_STRING,
          ),
        ),
        'filters' => array(
          'amount' => array('title' => E::ts('Payment Amount')),
        ),
      ),
      'civicrm_financial_trxn' => array(
        'dao' => 'CRM_Financial_DAO_FinancialTrxn',
        'fields' => array(
          'payment_instrument_id' => array(
            'title' => E::ts('Payment Method'),
            'default' => TRUE,
          ),
          'check_number' => array(
            'title' => E::ts('Check #'),
            'default' => TRUE,
          ),
          'pan_truncation' => array(
            'title' => E::ts('Last 4 digits of the card'),
            'default' => TRUE,
          ),
          'card_type_id' => array(
            'title' => E::ts('Card Type'),
            'default' => TRUE,
          ),
          'payment_processor_id' => array(
            'title' => E::ts('Payment Processor'),
            'default' => TRUE,
          ),
          'currency' => array(
            'title' => E::ts('Currency'),
            'default' => TRUE,
          ),
          'trxn_date' => array(
            'title' => E::ts('Transaction Date'),
            'default' => TRUE,
            'type' => CRM_Utils_Type::T_DATE,
          ),
        ),
        'filters' => array(
          'payment_instrument_id' => array(
            'title' => E::ts('Payment Method'),
            'type' => CRM_Utils_Type::T_INT,
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Contribute_PseudoConstant::paymentInstrument(),
          ),
          'payment_processor_id' => array(
            'title' => E::ts('Payment Processor'),
            'type' => CRM_Utils_Type::T_INT,
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Core_PseudoConstant::paymentProcessor(),
          ),
          'card_type_id' => array(
            'title' => E::ts('Card Type'),
            'type' => CRM_Utils_Type::T_INT,
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Financial_DAO_FinancialTrxn::buildOptions('card_type_id'),
          ),
          'currency' => array(
            'title' => E::ts('Currency'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Core_OptionGroup::values('currencies_enabled'),
            'default' => NULL,
            'type' => CRM_Utils_Type::T_STRING,
          ),
          'trxn_date' => array(
            'title' => E::ts('Transaction Date'),
            'operatorType' => CRM_Report_Form::OP_DATE,
            'type' => CRM_Utils_Type::T_DATE,
          ),
        ),
        'order_bys' => array(
          'payment_instrument_id' => array('title' => E::ts('Payment Method')),
          'check_number' => array('title' => E::ts('Check')),
          'card_type_id' => array('title' => E::ts('Card Type')),
          'trxn_date' => array('title' => E::ts('Transaction Date')),
        ),
      ),
      
      'civicrm_contribution' => array(
        'dao' => 'CRM_Contribute_DAO_Contribution',
        'fields' => array(
          'contribution_id' => array(
            'name' => 'id',
            'no_display' => TRUE,
            'required' => TRUE,
            'title' => ts(''),
          ),
          'id' => array(
            'name' => 'id',
            'title' => ts('Contribution ID'),
          ),
          'trxn_id' => array(
            'title' => E::ts('Transaction ID'),
            'no_repeat' => FALSE,
          ),
          'contribution_status_id' => array(
            'title' => E::ts('Status'),
            'no_repeat' => FALSE,
          ),
          'invoice_number' => array(
            'title' => E::ts('Invoice Number'),
            'no_repeat' => FALSE,
          ),
        ),
        'filters' => array(
          'contribution_status_id' => array(
            'title' => ts('Contribution Status'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Contribute_PseudoConstant::contributionStatus(),
            'default' => array(1),
            'type' => CRM_Utils_Type::T_INT,
          ),
          'trxn_id' => array('title' => E::ts('Transaction ID')),
        ),
        'order_bys' => array(
          'contribution_status_id' => array('title' => E::ts('Contribution Status')),
          'invoice_number' => array('title' => E::ts('Invoice Number')),
        ),
      ),
    );
    $this->_groupFilter = TRUE;
    $this->_tagFilter = TRUE;
    parent::__construct();
  }

  function preProcess() {
    $this->assign('reportTitle', E::ts('Payment Detail Report'));
    parent::preProcess();
  }

  function select() {
    $select = $this->_columnHeaders = array();

    foreach ($this->_columns as $tableName => $table) {
      if (array_key_exists('fields', $table)) {
        foreach ($table['fields'] as $fieldName => $field) {
          if (CRM_Utils_Array::value('required', $field) ||
            CRM_Utils_Array::value($fieldName, $this->_params['fields'])
          ) {
            $select[] = "{$field['dbAlias']} as {$tableName}_{$fieldName}";
            $this->_columnHeaders["{$tableName}_{$fieldName}"]['title'] = $field['title'];
            $this->_columnHeaders["{$tableName}_{$fieldName}"]['type'] = CRM_Utils_Array::value('type', $field);
          }
        }
      }
    }

    $this->_select = "SELECT " . implode(', ', $select) . " ";
  }

  function from() {
    $this->_from = NULL;

    $this->_from = "
         FROM  civicrm_contribution {$this->_aliases['civicrm_contribution']} {$this->_aclFrom}
               LEFT  JOIN civicrm_contact {$this->_aliases['civicrm_contact']}
                          ON {$this->_aliases['civicrm_contact']}.id =
                             {$this->_aliases['civicrm_contribution']}.contact_id 
              LEFT JOIN civicrm_entity_financial_trxn {$this->_aliases['civicrm_entity_financial_trxn']}
                    ON ({$this->_aliases['civicrm_contribution']}.id = {$this->_aliases['civicrm_entity_financial_trxn']}.entity_id AND
                        {$this->_aliases['civicrm_entity_financial_trxn']}.entity_table = 'civicrm_contribution')
              LEFT JOIN civicrm_financial_trxn {$this->_aliases['civicrm_financial_trxn']}
                    ON {$this->_aliases['civicrm_financial_trxn']}.id = {$this->_aliases['civicrm_entity_financial_trxn']}.financial_trxn_id
              LEFT JOIN civicrm_entity_financial_trxn {$this->_aliases['civicrm_entity_financial_trxn']}_item
                    ON ({$this->_aliases['civicrm_financial_trxn']}.id = {$this->_aliases['civicrm_entity_financial_trxn']}_item.financial_trxn_id AND
                        {$this->_aliases['civicrm_entity_financial_trxn']}_item.entity_table = 'civicrm_financial_item')
              ";
  }

  function where() {
    $clauses = array();
    foreach ($this->_columns as $tableName => $table) {
      if (array_key_exists('filters', $table)) {
        foreach ($table['filters'] as $fieldName => $field) {
          $clause = NULL;
          if (CRM_Utils_Array::value('operatorType', $field) & CRM_Utils_Type::T_DATE) {
            $relative = CRM_Utils_Array::value("{$fieldName}_relative", $this->_params);
            $from     = CRM_Utils_Array::value("{$fieldName}_from", $this->_params);
            $to       = CRM_Utils_Array::value("{$fieldName}_to", $this->_params);

            $clause = $this->dateClause($field['name'], $relative, $from, $to, $field['type']);
          }
          else {
            $op = CRM_Utils_Array::value("{$fieldName}_op", $this->_params);
            if ($op) {
              $clause = $this->whereClause($field,
                $op,
                CRM_Utils_Array::value("{$fieldName}_value", $this->_params),
                CRM_Utils_Array::value("{$fieldName}_min", $this->_params),
                CRM_Utils_Array::value("{$fieldName}_max", $this->_params)
              );
            }
          }

          if (!empty($clause)) {
            $clauses[] = $clause;
          }
        }
      }
    }
    
    $clauses[] = "{$this->_aliases['civicrm_financial_trxn']}.is_payment = 1";

    if (empty($clauses)) {
      $this->_where = "WHERE ( 1 ) ";
    }
    else {
      $this->_where = "WHERE " . implode(' AND ', $clauses);
    }

    if ($this->_aclWhere) {
      $this->_where .= " AND {$this->_aclWhere} ";
    }
  }
  
  function postProcess() {

    $this->beginPostProcess();

    // get the acl clauses built before we assemble the query
    $this->buildACLClause($this->_aliases['civicrm_contact']);
    $sql = $this->buildQuery(TRUE);

    $rows = array();
    $this->buildRows($sql, $rows);

    $this->formatDisplay($rows);
    $this->doTemplateAssignment($rows);
    $this->endPostProcess($rows);
  }

  function alterDisplay(&$rows) {
    // custom code to alter rows
    $entryFound = FALSE;
    $checkList = array();
    foreach ($rows as $rowNum => $row) {

      if (!empty($this->_noRepeats) && $this->_outputMode != 'csv') {
        // not repeat contact display names if it matches with the one
        // in previous row
        $repeatFound = FALSE;
        foreach ($row as $colName => $colVal) {
          if (CRM_Utils_Array::value($colName, $checkList) &&
            is_array($checkList[$colName]) &&
            in_array($colVal, $checkList[$colName])
          ) {
            $rows[$rowNum][$colName] = "";
            $repeatFound = TRUE;
          }
          if (in_array($colName, $this->_noRepeats)) {
            $checkList[$colName][] = $colVal;
          }
        }
      }
      
      if (array_key_exists('civicrm_financial_trxn_payment_instrument_id', $row)) {
        if ($value = $row['civicrm_financial_trxn_payment_instrument_id']) {
          $rows[$rowNum]['civicrm_financial_trxn_payment_instrument_id'] = CRM_Core_PseudoConstant::getLabel('CRM_Core_BAO_FinancialTrxn', 'payment_instrument_id', $value);
        }
        $entryFound = TRUE;
      }
      if (array_key_exists('civicrm_financial_trxn_payment_processor_id', $row)) {
        if ($value = $row['civicrm_financial_trxn_payment_processor_id']) {
          $rows[$rowNum]['civicrm_financial_trxn_payment_processor_id'] = CRM_Core_DAO::getFieldValue('CRM_Financial_DAO_PaymentProcessor', $value, 'name');
        }
        $entryFound = TRUE;
      }
      if (array_key_exists('civicrm_financial_trxn_card_type_id', $row)) {
        if ($value = $row['civicrm_financial_trxn_card_type_id']) {
          $rows[$rowNum]['civicrm_financial_trxn_card_type_id'] = CRM_Core_PseudoConstant::getLabel('CRM_Core_BAO_FinancialTrxn', 'card_type_id', $value);
        }
        $entryFound = TRUE;
      }
      if (array_key_exists('civicrm_contribution_contribution_status_id', $row)) {
        $contributionStatuses = CRM_Core_OptionGroup::values('contribution_status',
          FALSE, FALSE, FALSE, NULL, 'name', FALSE
        );
        if ($value = $row['civicrm_contribution_contribution_status_id']) {
          $rows[$rowNum]['civicrm_contribution_contribution_status_id'] = CRM_Utils_Array::value($value,$contributionStatuses);
        }
        $entryFound = TRUE;
      }
      
      if (array_key_exists('civicrm_contact_sort_name', $row) &&
        $rows[$rowNum]['civicrm_contact_sort_name'] &&
        array_key_exists('civicrm_contact_id', $row)
      ) {
        $url = CRM_Utils_System::url("civicrm/contact/view",
          'reset=1&cid=' . $row['civicrm_contact_id'],
          $this->_absoluteUrl
        );
        $rows[$rowNum]['civicrm_contact_sort_name_link'] = $url;
        $rows[$rowNum]['civicrm_contact_sort_name_hover'] = E::ts("View Contact Summary for this Contact.");
        $entryFound = TRUE;
      }
      if ( array_key_exists('civicrm_contribution_id', $row) ) {
        $url = CRM_Utils_System::url("civicrm/contact/view/contribution",
          'reset=1&id=' . $row['civicrm_contribution_id'].'&cid=' . $row['civicrm_contact_id'].'&action=view',
          $this->_absoluteUrl
        );
        $rows[$rowNum]['civicrm_contribution_id_link'] = $url;
        $rows[$rowNum]['civicrm_contribution_id_hover'] = E::ts("View Contribution summary");
        $rows[$rowNum]['civicrm_contribution_id_class'] = E::ts("crm-popup");
        $entryFound = TRUE;
      }

      if (!$entryFound) {
        break;
      }
    }
  }
  /**
   * @param $rows
   *
   * @return array
   */
  public function statistics(&$rows) {
    $statistics = parent::statistics($rows);

    $totalAmount = $average = $fees = $net = array();
    $count = 0;
    $select = "
        SELECT COUNT({$this->_aliases['civicrm_contribution']}.total_amount ) as count,
               SUM( {$this->_aliases['civicrm_contribution']}.total_amount ) as amount,
               ROUND(AVG({$this->_aliases['civicrm_contribution']}.total_amount), 2) as avg,
               {$this->_aliases['civicrm_contribution']}.currency as currency,
               SUM( {$this->_aliases['civicrm_contribution']}.fee_amount ) as fees,
               SUM( {$this->_aliases['civicrm_contribution']}.net_amount ) as net
        ";

    $group = "\nGROUP BY {$this->_aliases['civicrm_contribution']}.currency";
    $sql = "{$select} {$this->_from} {$this->_where} {$group}";
    $dao = CRM_Core_DAO::executeQuery($sql);
    $this->addToDeveloperTab($sql);

    while ($dao->fetch()) {
      $totalAmount[] = CRM_Utils_Money::format($dao->amount, $dao->currency) . " (" . $dao->count . ")";
      $fees[] = CRM_Utils_Money::format($dao->fees, $dao->currency);
      $net[] = CRM_Utils_Money::format($dao->net, $dao->currency);
      $average[] = CRM_Utils_Money::format($dao->avg, $dao->currency);
      $count += $dao->count;
    }
    $statistics['counts']['amount'] = array(
      'title' => ts('Total Amount (Contributions)'),
      'value' => implode(',  ', $totalAmount),
      'type' => CRM_Utils_Type::T_STRING,
    );
    $statistics['counts']['count'] = array(
      'title' => ts('Total Contributions'),
      'value' => $count,
    );
    $statistics['counts']['fees'] = array(
      'title' => ts('Fees'),
      'value' => implode(',  ', $fees),
      'type' => CRM_Utils_Type::T_STRING,
    );
    $statistics['counts']['net'] = array(
      'title' => ts('Net'),
      'value' => implode(',  ', $net),
      'type' => CRM_Utils_Type::T_STRING,
    );
    $statistics['counts']['avg'] = array(
      'title' => ts('Average'),
      'value' => implode(',  ', $average),
      'type' => CRM_Utils_Type::T_STRING,
    );
    return $statistics;
  }

}
