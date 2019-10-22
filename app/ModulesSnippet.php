<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Asset_fund.
 *
 */
class ModulesSnippet extends Model
{

    protected $table = 'modules_snippet';

    protected $primaryKey = 'id';

    public $timestamps = false;

    public static function getModuleSnippet($moduleId) {
        $moduleSnippetFile = ModulesSnippet::where('module_id',$moduleId)
            ->get()->toArray();
        return $moduleSnippetFile;
    }
}
