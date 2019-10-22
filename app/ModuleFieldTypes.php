<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModuleFieldTypes extends Model
{
    protected $table = 'module_field_types';
    
    protected $fillable = [
        "name"
    ];
    
    protected $hidden = [
        
    ];
    
    // ModuleFieldTypes::getFTypes()
    public static function getFTypes() {
        $fields = ModuleFieldTypes::all();
        $fields2 = array();
        foreach ($fields as $field) {
            $fields2[$field['name']] = $field['id'];
        }
        return $fields2;
    }
    
    public static function getFieldTypeValue() {
        $fields = ModuleFieldTypes::all();
        $fields2 = array();
        foreach ($fields as $field) {
            $fields2[$field['id']] = $field['name'];
        }
        return $fields2;
    }

    public static function getFieldTypeId($columnStringType,$columnKey,$columnName)
    {
        $maxLength = '';
        $columnTypeArray = explode("(",$columnStringType);
        $columnType = $columnTypeArray[0];
        $precisionValue = 0;
        if(isset($columnTypeArray[1]))
        {
            $maxLength = substr($columnTypeArray[1], 0, -1);
        }
        
        switch ($columnType) {
            case 'int':
            case 'smallint':
            case 'tinyint':
            case 'mediumint':
            case 'bigint':
            case 'bit':
            case 'boolean':
            case 'serial':
                if($columnKey === 'MUL')
                {
                    if(preg_match('(type_list)i', $columnName) === 1)
                    {
                        $fieldType = 'AutoComplete';
                    } else if(preg_match('(type)i', $columnName) === 1) {
                        $fieldType = 'Dropdown';
                    } else {
                        $fieldType = 'AutoComplete';
                    }
                } else if(preg_match('(telephone|mobile|phone)i', $columnName) === 1) {
                    $fieldType = 'Mobile';
                } else {
                    $fieldType = 'Integer';
                }
                break;
            case 'varchar':
            case 'time':
            case 'year':
            case 'char':
            case 'tinytext':
            case 'binary':
            case 'varbinary':
            case 'tinyblob':
            case 'enum':
            case 'set':
            case 'geometry':
            case 'point':
            case 'linestring':
            case 'poligon':
            case 'multipoint':
            case 'multilinestring':
            case 'multipoligon':
            case 'geometrycollecion':
                if(preg_match('(link|url|href)i', $columnName) === 1) {
                    $fieldType = 'URL';
                } else if(self::endsWith($columnName,'website')) {
                    $fieldType = 'URL';
                } else if(preg_match('(image|photo|logo|banner)i', $columnName) === 1) {
                    $fieldType = 'Image';
                } else if(preg_match('(email)i', $columnName) === 1) {
                    $fieldType = 'Email';
                } else if(preg_match('(password)i', $columnName) === 1) {
                    $fieldType = 'Password';
                } else if(preg_match('(description)i', $columnName) === 1) {
                    $fieldType = 'Textarea';
                } else {
                    $fieldType = 'String';
                }
                break;
            case 'text':
            case 'mediumtext':
                if(preg_match('(description)i', $columnName) === 1) {
                    $fieldType = 'Textarea';
                } else {
                    $fieldType = 'TextField';
                }
                break;
            case 'longtext' :
            case 'mediumblob':
            case 'blob':
            case 'longblob':
                $fieldType = 'textarea';
                break;
            case 'decimal':
                if(preg_match('(quantity|Price)i', $columnName) === 1) {
                    $fieldType = 'Currency';
                } else {
                    $fieldType = 'Decimal';
                }
                $decimalLength = explode(',', $maxLength);
                $maxLength = $decimalLength[0];
                $precisionValue = $decimalLength[1];
                break;
            case 'float':
            case 'double':
            case 'real':
                $fieldType = 'Float';
                break;
            case 'date':
                $fieldType = 'Date';
                break;
            case 'datetime':
            case 'timestamp':
                $fieldType = 'Datetime';
                break;
            default:
                $fieldType = 'String';
                break;
        } 
        $fieldtypeId = ModuleFieldTypes::select('id')->where('name',$fieldType)->get()->first()->id;
        $fieldTypeLengthDetails['columnTypeId'] = $fieldtypeId;
        $fieldTypeLengthDetails['columnMaxLength'] = $maxLength;
        $fieldTypeLengthDetails['columnPrecision'] = $precisionValue;
        return $fieldTypeLengthDetails;
    }

    public static function endsWith($string, $test) {
        $strlen = strlen($string);
        $testlen = strlen($test);
        if ($testlen > $strlen) return false;
        return substr_compare($string, $test, $strlen - $testlen, $testlen) === 0;
    }
}
