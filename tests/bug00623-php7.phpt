--TEST--
Test for bug #623: Static properties of a class can be evaluated only with difficulty (>= PHP 7.0)
--SKIPIF--
<?php if (getenv("SKIP_DBGP_TESTS")) { exit("skip Excluding DBGp tests"); } ?>
<?php if (!version_compare(phpversion(), "7.0", '>=')) echo "skip >= PHP 7.0 needed\n"; ?>
--FILE--
<?php
require 'dbgp/dbgpclient.php';
$data = file_get_contents(dirname(__FILE__) . '/bug00623.inc');

$commands = array(
	'breakpoint_set -t line -f file:///tmp/xdebug-dbgp-test.php -n 12',
	'breakpoint_set -t line -f file:///tmp/xdebug-dbgp-test.php -n 24',
	'run',
	'context_get',
	'property_get -n ::',
	'property_get -n ::*testclassDaddy*daddyPriv',
	'property_get -n ::nameProt::*testclassDaddy*daddyPriv',
	'property_get -n ::*testclassDaddy*daddyPriv[3]',
	'run',
	'context_get',
	'property_get -n t',
	'property_get -n $t',
	'property_get -n $t::',
	'property_get -n $t::nameProt',
	'detach',
);

dbgpRun( $data, $commands );
?>
--EXPECTF--
<?xml version="1.0" encoding="iso-8859-1"?>
<init xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" fileuri="file:///tmp/xdebug-dbgp-test.php" language="PHP" protocol_version="1.0" appid="" idekey=""><engine version=""><![CDATA[Xdebug]]></engine><author><![CDATA[Derick Rethans]]></author><url><![CDATA[http://xdebug.org]]></url><copyright><![CDATA[Copyright (c) 2002-%d by Derick Rethans]]></copyright></init>

-> breakpoint_set -i 1 -t line -f file:///tmp/xdebug-dbgp-test.php -n 12
<?xml version="1.0" encoding="iso-8859-1"?>
<response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="breakpoint_set" transaction_id="1" id=""></response>

-> breakpoint_set -i 2 -t line -f file:///tmp/xdebug-dbgp-test.php -n 24
<?xml version="1.0" encoding="iso-8859-1"?>
<response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="breakpoint_set" transaction_id="2" id=""></response>

-> run -i 3
<?xml version="1.0" encoding="iso-8859-1"?>
<response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="run" transaction_id="3" status="break" reason="ok"><xdebug:message filename="file:///tmp/xdebug-dbgp-test.php" lineno="12"></xdebug:message></response>

-> context_get -i 4
<?xml version="1.0" encoding="iso-8859-1"?>
<response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="context_get" transaction_id="4" context="0"><property name="::" fullname="::" type="object" classname="testclass" children="1" numchildren="3"><property name="::nameProt" fullname="::nameProt" address="" type="object" classname="testclass" children="1" numchildren="3" page="0" pagesize="32" facet="static protected"><property name="nameProt" fullname="::nameProt::nameProt" facet="static protected" address="" type="object" classname="testclass" children="1" numchildren="3"></property><property name="namePriv" fullname="::nameProt::namePriv" facet="static private" address="" type="null"></property><property name="*testclassDaddy*daddyPriv" fullname="::nameProt::*testclassDaddy*daddyPriv" facet="static private" address="" type="array" children="1" numchildren="4"></property></property><property name="::namePriv" fullname="::namePriv" address="" type="null" facet="static private"></property><property name="::*testclassDaddy*daddyPriv" fullname="::*testclassDaddy*daddyPriv" address="" type="array" children="1" numchildren="4" page="0" pagesize="32" facet="static private"><property name="0" fullname="::*testclassDaddy*daddyPriv[0]" address="" type="int"><![CDATA[1]]></property><property name="1" fullname="::*testclassDaddy*daddyPriv[1]" address="" type="int"><![CDATA[2]]></property><property name="2" fullname="::*testclassDaddy*daddyPriv[2]" address="" type="int"><![CDATA[3]]></property><property name="3" fullname="::*testclassDaddy*daddyPriv[3]" address="" type="int"><![CDATA[9]]></property></property></property></response>

-> property_get -i 5 -n ::
<?xml version="1.0" encoding="iso-8859-1"?>
<response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="property_get" transaction_id="5" status="break" reason="ok"><error code="300"><message><![CDATA[can not get property]]></message></error></response>

-> property_get -i 6 -n ::*testclassDaddy*daddyPriv
<?xml version="1.0" encoding="iso-8859-1"?>
<response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="property_get" transaction_id="6"><property name="::*testclassDaddy*daddyPriv" fullname="::*testclassDaddy*daddyPriv" address="" type="array" children="1" numchildren="4" page="0" pagesize="32"><property name="0" fullname="::*testclassDaddy*daddyPriv[0]" address="" type="int"><![CDATA[1]]></property><property name="1" fullname="::*testclassDaddy*daddyPriv[1]" address="" type="int"><![CDATA[2]]></property><property name="2" fullname="::*testclassDaddy*daddyPriv[2]" address="" type="int"><![CDATA[3]]></property><property name="3" fullname="::*testclassDaddy*daddyPriv[3]" address="" type="int"><![CDATA[9]]></property></property></response>

-> property_get -i 7 -n ::nameProt::*testclassDaddy*daddyPriv
<?xml version="1.0" encoding="iso-8859-1"?>
<response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="property_get" transaction_id="7"><property name="::nameProt::*testclassDaddy*daddyPriv" fullname="::nameProt::*testclassDaddy*daddyPriv" address="" type="array" children="1" numchildren="4" page="0" pagesize="32"><property name="0" fullname="::nameProt::*testclassDaddy*daddyPriv[0]" address="" type="int"><![CDATA[1]]></property><property name="1" fullname="::nameProt::*testclassDaddy*daddyPriv[1]" address="" type="int"><![CDATA[2]]></property><property name="2" fullname="::nameProt::*testclassDaddy*daddyPriv[2]" address="" type="int"><![CDATA[3]]></property><property name="3" fullname="::nameProt::*testclassDaddy*daddyPriv[3]" address="" type="int"><![CDATA[9]]></property></property></response>

-> property_get -i 8 -n ::*testclassDaddy*daddyPriv[3]
<?xml version="1.0" encoding="iso-8859-1"?>
<response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="property_get" transaction_id="8"><property name="::*testclassDaddy*daddyPriv[3]" fullname="::*testclassDaddy*daddyPriv[3]" address="" type="int"><![CDATA[9]]></property></response>

-> run -i 9
<?xml version="1.0" encoding="iso-8859-1"?>
<response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="run" transaction_id="9" status="break" reason="ok"><xdebug:message filename="file:///tmp/xdebug-dbgp-test.php" lineno="24"></xdebug:message></response>

-> context_get -i 10
<?xml version="1.0" encoding="iso-8859-1"?>
<response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="context_get" transaction_id="10" context="0"><property name="$t" fullname="$t" address="" type="object" classname="testclass" children="1" numchildren="3" page="0" pagesize="32"><property name="nameProt" fullname="$t::nameProt" facet="static protected" address="" type="object" classname="testclass" children="1" numchildren="3"></property><property name="namePriv" fullname="$t::namePriv" facet="static private" address="" type="null"></property><property name="*testclassDaddy*daddyPriv" fullname="$t::*testclassDaddy*daddyPriv" facet="static private" address="" type="array" children="1" numchildren="4"></property></property></response>

-> property_get -i 11 -n t
<?xml version="1.0" encoding="iso-8859-1"?>
<response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="property_get" transaction_id="11"><property name="$t" fullname="$t" address="" type="object" classname="testclass" children="1" numchildren="3" page="0" pagesize="32"><property name="nameProt" fullname="$t::nameProt" facet="static protected" address="" type="object" classname="testclass" children="1" numchildren="3"></property><property name="namePriv" fullname="$t::namePriv" facet="static private" address="" type="null"></property><property name="*testclassDaddy*daddyPriv" fullname="$t::*testclassDaddy*daddyPriv" facet="static private" address="" type="array" children="1" numchildren="4"></property></property></response>

-> property_get -i 12 -n $t
<?xml version="1.0" encoding="iso-8859-1"?>
<response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="property_get" transaction_id="12"><property name="$t" fullname="$t" address="" type="object" classname="testclass" children="1" numchildren="3" page="0" pagesize="32"><property name="nameProt" fullname="$t::nameProt" facet="static protected" address="" type="object" classname="testclass" children="1" numchildren="3"></property><property name="namePriv" fullname="$t::namePriv" facet="static private" address="" type="null"></property><property name="*testclassDaddy*daddyPriv" fullname="$t::*testclassDaddy*daddyPriv" facet="static private" address="" type="array" children="1" numchildren="4"></property></property></response>

-> property_get -i 13 -n $t::
<?xml version="1.0" encoding="iso-8859-1"?>
<response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="property_get" transaction_id="13"><property name="$t" fullname="$t" address="" type="object" classname="testclass" children="1" numchildren="3" page="0" pagesize="32"><property name="nameProt" fullname="$t::nameProt" facet="static protected" address="" type="object" classname="testclass" children="1" numchildren="3"></property><property name="namePriv" fullname="$t::namePriv" facet="static private" address="" type="null"></property><property name="*testclassDaddy*daddyPriv" fullname="$t::*testclassDaddy*daddyPriv" facet="static private" address="" type="array" children="1" numchildren="4"></property></property></response>

-> property_get -i 14 -n $t::nameProt
<?xml version="1.0" encoding="iso-8859-1"?>
<response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="property_get" transaction_id="14"><property name="$t::nameProt" fullname="$t::nameProt" address="" type="object" classname="testclass" children="1" numchildren="3" page="0" pagesize="32"><property name="nameProt" fullname="$t::nameProt::nameProt" facet="static protected" address="" type="object" classname="testclass" children="1" numchildren="3"></property><property name="namePriv" fullname="$t::nameProt::namePriv" facet="static private" address="" type="null"></property><property name="*testclassDaddy*daddyPriv" fullname="$t::nameProt::*testclassDaddy*daddyPriv" facet="static private" address="" type="array" children="1" numchildren="4"></property></property></response>

-> detach -i 15
<?xml version="1.0" encoding="iso-8859-1"?>
<response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="detach" transaction_id="15" status="stopping" reason="ok"></response>
