<?php

require_once '../../common.php';
Auth\Session::grantAccess([]);
httpResponse::addHeader('<script src="/libs/jstree/jstree.min.js"></script>');
httpResponse::addHeader('<link rel="stylesheet" href="/libs/jstree/style.min.css">');
httpResponse::showHtmlHeader('Объекты');
httpResponse::showNavPills('../%s/', require '../sections.php', 'places');
?>
<hr>
<div id="container"></div>
<script>
$(function() {
    $('#container').jstree({
    'core' : {
        'data' : {
            "url" : "/api/places/"
        },
        "check_callback": function (operation, node, node_parent, node_position, more) {
            if(operation==='rename_node') {
                params={
                    "id": node.id,
                    "text": node_position
                };
                $.get('/api/places/rename/', params, function(result) {
                    if(result.error) {
                        alert(result.error);
                        return false;
                    }
                    return true;
                });
            }
            if(operation==='move_node') {
                params={
                    "place_id": node.id,
                    "parent": node_parent.id
                };
                $.get('/api/places/move/', params, function(result) {
                    if(result.error) {
                        alert(result.error);
                        return false;
                    }
                    return true;
                });
            }
            if(operation==='delete_node') {
                params={
                    "id": node.id
                };
                $.get('/api/places/delete/', params, function(result) {
                    if(result.error) {
                        alert(result.error);
                        return false;
                    }
                    return true;
                });
            }
            return true;
        }
    },
    "contextmenu":{         
    "items": function($node) {
        var tree = $("#container").jstree(true);
        return {
            "Create": {
                "label": "Добавить элемент",
                "action": function (obj) { 
                    params={
                        "text": "Новый элемент",
                        "parent": $node.id
                    };
                    $.get('/api/places/create/', params, function(result) {
                        if(result.error) {
                            alert(result.error);
                            return;
                        }
                        $node = tree.create_node($node, result);
                        tree.edit($node);
                    });
                }
            },
            "Rename": {
                "label": "Переименовать",
                "action": function (obj) { 
                    tree.edit($node);
                }
            },                         
            "Remove": {
                "label": "Удалить",
                "action": function (obj) { 
                    tree.delete_node($node);
                    }
                }
            };
        }
    },

    "state" : { "key" : "places_state" },
    "plugins" : ["state", "dnd", "contextmenu"]
    });
});
</script>
<?php
httpResponse::showHtmlFooter();