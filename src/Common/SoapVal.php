<?php

namespace NuSoap\Common;

use NuSoap\NuSoap;

/**
 * For creating serializable abstractions of native PHP types.  This class
 * allows element name/namespace, XSD type, and XML attributes to be
 * associated with a value.  This is extremely useful when WSDL is not
 * used, but is also useful when WSDL is used with polymorphic types, including
 * xsd:anyType and user-defined types.
 *
 * @author   Dietrich Ayala <dietrich@ganx4.com>
 * @version  $Id: Soapval.php,v 1.11 2007/04/06 13:56:32 snichol Exp $
 * @access   public
 */
class SoapVal extends NuSoap
{
    /**
     * The XML element name
     *
     * @var string
     * @access private
     */
    public $name;

    /**
     * The XML type name (string or false)
     *
     * @var mixed
     * @access private
     */
    public $type;

    /**
     * The PHP value
     *
     * @var mixed
     * @access private
     */
    public $value;

    /**
     * The XML element namespace (string or false)
     *
     * @var mixed
     * @access private
     */
    public $element_ns;

    /**
     * The XML type namespace (string or false)
     *
     * @var mixed
     * @access private
     */
    public $type_ns;

    /**
     * The XML element attributes (array or false)
     *
     * @var mixed
     * @access private
     */
    public $attributes;

    /**
     * constructor
     *
     * @param string $name optional name
     * @param mixed $type optional type name
     * @param mixed $value optional value
     * @param mixed $element_ns optional namespace of value
     * @param mixed $type_ns optional namespace of type
     * @param mixed $attributes associative array of attributes to add to element serialization
     * @access   public
     */
    public function __construct($name = 'soapval', $type = false, $value = -1, $element_ns = false, $type_ns = false, $attributes = false)
    {
        parent::__construct();
        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
        $this->element_ns = $element_ns;
        $this->type_ns = $type_ns;
        $this->attributes = $attributes;
    }

    /**
     * return serialized value
     *
     * @param string $use The WSDL use value (encoded|literal)
     * @return    string XML data
     * @access   public
     */
    public function serialize($use = 'encoded')
    {
        return $this->serialize_val(
            $this->value,
            $this->name,
            $this->type,
            $this->element_ns,
            $this->type_ns,
            $this->attributes,
            $use,
            true
        );
    }

    /**
     * decodes a soapval object into a PHP native type
     *
     * @return    mixed
     * @access   public
     */
    public function decode()
    {
        return $this->value;
    }
}