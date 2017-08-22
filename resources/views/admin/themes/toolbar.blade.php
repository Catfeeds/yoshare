<div class="cb-toolbar">操作:</div>
<div class="btn-group margin-bottom">
    <input type="hidden" name="state" id="state" value=""/>
    <button class="btn btn-primary btn-xs margin-r-5" id="create" onclick="create()">新增</button>
    <button class="btn btn-primary btn-xs margin-r-5" id="create" onclick="undo()">取消</button>
</div>
<div class="btn-group margin-bottom pull-right">
    <button type="button" class="btn btn-info btn-xs margin-r-5 filter" id="all">全部</button>
    <button type="button" class="btn btn-default btn-xs margin-r-5" id="query">查询</button>
    <div class="btn-group">
        <button type="button" class="btn btn-info btn-xs margin-t-5">变量列表</button>
        <button type="button" class="btn btn-info btn-xs margin-r-5 margin-t-5 dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><a href="#">$article</a></li>
            <li><a href="#">$article->title</a></li>
            <li><a href="#">$article->content</a></li>
        </ul>
    </div>
</div>