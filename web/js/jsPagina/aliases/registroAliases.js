var $collectionHolder;
var $addNewItem = $('<div class="form-group"><a href="#" class="btn btn-info btn-sm">Add new item</a></div>');
$(document).ready(function () 
{
    $collectionHolder = $('#exp_list');
    $collectionHolder.append($addNewItem);
    $collectionHolder.data('index', $collectionHolder.find('.panel').length);
    $collectionHolder.find('.panel').each(function () 
    {
        addRemoveButton($(this));
    });
    $addNewItem.click(function (e) 
    {
        e.preventDefault();
        addNewForm();
    })
});
function addNewForm() 
{
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype;
    newForm = newForm.replace(/__name__/g, index);
    $collectionHolder.data('index', index+1);
    var $panel = $('<div class="panel panel-warning"><div class="panel-heading"></div></div>');
    var $panelBody = $('<div class="panel-body"></div>').append(newForm);
    $panel.append($panelBody);
    addRemoveButton($panel);
    $addNewItem.before($panel);
}

/**
 * adds a remove button to the panel that is passed in the parameter
 * @param $panel
 */
function addRemoveButton ($panel)
{
    var $removeButton = $('<br><div class="form-group"><a href="#" class="btn btn-danger btn-sm">Remove</a></div>');
    var $panelFooter = $('<div class="panel-footer"></div>').append($removeButton);
    $removeButton.click(function (e) 
    {
        e.preventDefault();
        $(e.target).parents('.panel').slideUp(1000, function () 
        {
            $(this).remove();
        })
    });
    $panel.append($panelFooter);
}