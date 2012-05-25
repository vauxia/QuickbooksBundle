<?php

namespace AdvantageLabs\QuickbooksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// This file defines some important constants.
require '../vendor/intuit/php_devkit/Quickbooks.php';

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AdvantageLabsQuickbooksBundle:Default:index.html.twig', array('name' => $name));
    }

    public function qbwcAction()
    {
      // TODO these are all hard-coded for now. 
      // See docs at http://www.consolibyte.com/wiki/doku.php/quickbooks_web_connector
       $response = '<?xml version="1.0"?>
        <QBWCXML>
          <AppName>Advantage Labs contact manager</AppName>
          <AppID></AppID>
          <AppURL>https://manage/quickbooks/server.php</AppURL>
          <AppDescription></AppDescription>
          <AppSupport>http://www.advantagelabs.com/</AppSupport>
          <UserName>username</UserName>
          <OwnerID>{3f557aa0-999c-11e1-a8b0-0800200c9a66}</OwnerID>
          <FileID>{54c72230-999c-11e1-a8b0-0800200c9a66}</FileID>
          <QBType>QBFS</QBType>
          <Scheduler>
            <RunEveryNMinutes>2</RunEveryNMinutes>
          </Scheduler>
          <IsReadOnly>false</IsReadOnly>
        </QBWCXML>';

      return new Response($response);
    }

    public function serverAction()
    {
      new \QuickBooks_WebConnector_Server();

      // Map QuickBooks actions to handler functions
      $map = array(
	      QUICKBOOKS_ADD_CUSTOMER => array( '_quickbooks_customer_add_request', '_quickbooks_customer_add_response' ),
	      );
      
      // This is entirely optional, use it to trigger actions when an error is returned by QuickBooks
      $errmap = array(
	      '*' => '_quickbooks_error_catchall', 				// Using a key value of '*' will catch any errors which were not caught by another error handler
	      );
      
      // An array of callback hooks
      $hooks = array(
	      );
      
      // Logging level
      $log_level = QUICKBOOKS_LOG_DEVELOP;		// Use this level until you're sure everything works!!!
      
      // What SOAP server you're using 
      $soapserver = QUICKBOOKS_SOAPSERVER_BUILTIN;		// A pure-PHP SOAP server (no PHP ext/soap extension required, also makes debugging easier)
      
      $soap_options = array(		// See http://www.php.net/soap
	      );
      
      $handler_options = array(
	      'deny_concurrent_logins' => false, 
	      );		// See the comments in the QuickBooks/Server/Handlers.php file
      
      $driver_options = array(		// See the comments in the QuickBooks/Driver/<YOUR DRIVER HERE>.php file ( i.e. 'Mysql.php', etc. )
	      );
      
      $callback_options = array(
	      );
      
      // Create a new server and tell it to handle the requests
      // __construct($dsn_or_conn, $map, $errmap = array(), $hooks = array(), $log_level = QUICKBOOKS_LOG_NORMAL, $soap = QUICKBOOKS_SOAPSERVER_PHP, $wsdl = QUICKBOOKS_WSDL, $soap_options = array(), $handler_options = array(), $driver_options = array(), $callback_options = array()
      $Server = new \QuickBooks_WebConnector_Server($dsn, $map, $errmap, $hooks, $log_level, $soapserver, QUICKBOOKS_WSDL, $soap_options, $handler_options, $driver_options, $callback_options);
      $response = $Server->handle(true, true);
      
          }
      }
