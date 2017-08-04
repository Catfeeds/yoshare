<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleField extends Model
{
    const TYPE_INTEGER = 1;
    const TYPE_FLOAT = 2;
    const TYPE_TEXT = 3;
    const TYPE_LONG_TEXT = 3;
    const TYPE_DATETIME = 5;
    const TYPE_HTML = 6;
    const TYPE_ENTITY = 7;
    const TYPE_IMAGE = 8;
    const TYPE_AUDIO = 9;
    const TYPE_VIDEO = 10;
    const TYPE_IMAGES = 11;
    const TYPE_AUDIOS = 12;
    const TYPE_VIDEOS = 13;

    const TYPES = [
        1 => '整数',
        2 => '浮点数',
        3 => '文本',
        4 => '长文本',
        5 => '日期时间',
        6 => 'HTML',
        7 => '实体引用',
        8 => '图片',
        9 => '音频',
        10 => '视频',
        11 => '图片集',
        12 => '音频集',
        13 => '视频集',
    ];

    const EDITOR_TYPE_TEXT = 1;
    const EDITOR_TYPE_TEXTAREA = 2;
    const EDITOR_TYPE_SELECT_SINGLE = 3;
    const EDITOR_TYPE_SELECT_MULTI = 4;
    const EDITOR_TYPE_DATETIME = 5;
    const EDITOR_TYPE_HTML = 6;
    const EDITOR_TYPE_ENTITY = 7;
    const EDITOR_TYPE_IMAGE = 8;
    const EDITOR_TYPE_AUDIO = 9;
    const EDITOR_TYPE_VIDEO = 10;
    const EDITOR_TYPE_IMAGES = 11;
    const EDITOR_TYPE_AUDIOS = 12;
    const EDITOR_TYPE_VIDEOS = 13;

    const EDITOR_TYPES = [
        1 => '文本',
        2 => '多行文本',
        3 => '单选',
        4 => '多选',
        5 => '日期时间',
        6 => '富文本',
        7 => '实体',
        8 => '图片',
        9 => '音频',
        10 => '视频',
        11 => '图片集',
        12 => '音频集',
        13 => '视频集',
    ];

    const COLUMN_ALIGN_LEFT = 1;
    const COLUMN_ALIGN_CENTER = 2;
    const COLUMN_ALIGN_RIGHT = 3;

    const COLUMN_ALIGNS = [
        1 => '左',
        2 => '中',
        3 => '右',
    ];

    protected $fillable = [
        'name',
        'alias',
        'type',
        'length',
    ];

    public function typeName()
    {
        return array_key_exists($this->type, static::TYPES) ? static::TYPES[$this->type] : '';
    }

    public function editorTypeName()
    {
        return array_key_exists($this->editor_type, static::EDITOR_TYPES) ? static::EDITOR_TYPES[$this->editor_type] : '';
    }

    public function columnAlignName()
    {
        return array_key_exists($this->column_align, static::COLUMN_ALIGNS) ? static::COLUMN_ALIGNS[$this->column_align] : '';
    }
}
