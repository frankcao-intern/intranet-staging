var coreEngine = coreEngine || {};
(function($, w) {
    "use strict";
    $.extend(coreEngine, {
        page_properties: {
            setConfirmUnload: function(on) {
                var unloadMessage = function() {
                    return 'You have changed settings on this page. If you navigate away from this page without ' + 'first saving, the changes will be lost.';
                };
                $(w).on('beforeunload', (on) ? unloadMessage : null);
            },
            split: function(val) {
                return val.split(/,\s*/);
            },
            setup: function() {
                if ($.fn.mask && $.fn.datepicker) {
                    $.mask.definitions['1'] = '[01]';
                    $.mask.definitions['3'] = '[0-3]';
                    $("#expiration_date").datepicker({
                        dateFormat: "yy-mm-dd"
                    }).mask("9999-19-39");
                    $("#date_published").datepicker({
                        dateFormat: "yy-mm-dd"
                    }).mask("9999-19-39");
                    $("#show_until").datepicker({
                        dateFormat: "yy-mm-dd"
                    }).mask("9999-19-39");
                    $("#featured_from").datepicker({
                        dateFormat: "yy-mm-dd"
                    }).mask("9999-19-39");
                    $("#featured_until").datepicker({
                        dateFormat: "yy-mm-dd"
                    }).mask("9999-19-39");
                }
                if ($.fn.checkbox) {
                    $("input[type=checkbox].js-gen-settings").checkbox({
                        empty: coreEngine.siteRoot + 'assets/images/empty.png'
                    });
                }
                $('#sections').selectable({
                    selected: function() {
                        coreEngine.page_properties.setConfirmUnload(true);
                    },
                    unselected: function() {
                        coreEngine.page_properties.setConfirmUnload(true);
                    }
                });
                $('.select-c.templates').fancySelect();
                $('input, select').change(function() {
                    coreEngine.page_properties.setConfirmUnload(true);
                });
            },
            tags: function() {
                var extractLast = function(term) {
                    return coreEngine.page_properties.split(term).pop();
                };
                $("#tags").bind("keydown", function(event) {
                    if (event.keyCode === $.ui.keyCode.TAB && $(this).data("autocomplete").menu.active) {
                        event.preventDefault();
                    }
                }).autocomplete({
                    minLength: 1,
                    source: function(request, response) {
                        coreEngine.ajax("server/gettags/" + (new Date()).getTime(), "tag_name=" + extractLast(request.term), response, 'json');
                    },
                    focus: function() {
                        return false;
                    },
                    select: function(event, ui) {
                        var terms = coreEngine.page_properties.split(this.value);
                        terms.pop();
                        terms.push(ui.item.value);
                        terms.push("");
                        this.value = terms.join(", ");
                        return false;
                    }
                });
            },
            saveProps: function() {
                $(".btn-save-prop").button().bind('click', function(event) {
                    var date_published = Date.parse($("#date_published").val()),
                        show_until = Date.parse($("#show_until").val()),
                        featured_from = Date.parse($("#featured_from").val()),
                        featured_until = Date.parse($("#featured_until").val()),
                        tags = $("#tags"),
                        settings = {},
                        answer, getSections, postData, id;
                    if ($("#expiration_date").val()) {
                        answer = w.confirm("You have selected an Expiration Date, this page will be deleted " + "at the end of that day. Are you sure?");
                        if (!answer) {
                            event.stopImmediatePropagation();
                            return false;
                        }
                    }
                    if (show_until < date_published) {
                        $.message("Show Until cannot be earlier than Date Published, please correct this and " + "try again.", 'error');
                        event.stopImmediatePropagation();
                        return false;
                    }
                    if (featured_until < featured_from) {
                        $.message("Featured Until cannot be earlier than Featured From, please correct this and " + "try again.", 'error');
                        event.stopImmediatePropagation();
                        return false;
                    }
                    settings.sections = [];
                    getSections = function() {
                        id = $(this).attr("id");
                        settings.sections.push(id.substr(1));
                    };
                    $("ul#sections li.ui-selected").each(getSections);
                    $("ul.sections-other li").each(getSections);
                    if (tags.length > 0) {
                        tags = tags.val();
                        tags = tags.replace(/ *,/g, ",");
                        tags = tags.replace(/, */g, ",");
                        tags = tags.split(",");
                        if ((tags[tags.length - 1] === "") || (tags[tags.length - 1] === " ")) {
                            tags.pop();
                        }
                        settings.tags = tags;
                    }
                    $(".js-gen-settings").each(function() {
                        if ($(this).is(":checkbox, :radio")) {
                            settings[this.name] = $(this).is(":checked");
                        } else {
                            settings[this.name] = this.value || null;
                        }
                    });
                    postData = "pid=" + coreEngine.pageID;
                    postData += "&data=" + JSON.stringify(settings);
					alert(postData);
                    coreEngine.ajax("properties/updatepage/" + (new Date()).getTime(), postData, function(result) {
                        var permissions = [],
                            postData;
                        if (result.isError) {
                            $.message(result.errorStr, 'error');
                        } else {
                            $.message(result.data, 'success');
                            $("#propPermissions tr").each(function() {
                                permissions.push(coreEngine.page_properties.getPerms($(this)));
                            });
                            postData = "pid=" + coreEngine.pageID.match(/\d+/)[0];
                            postData += "&data=" + JSON.stringify(permissions);
							alert(postData);
                            coreEngine.ajax("/server/permupdate/" + (new Date()).getTime(), postData, coreEngine.genericCallBack, 'json');
                            coreEngine.page_properties.setConfirmUnload(false);
                            return false;
                        }
                    }, 'json');
                    coreEngine.page_properties.setConfirmUnload(false);
                    return false;
                });
            },
            getPerms: function($tr) {
                var perm = {},
                    gid = $tr.find("th").attr("id");
                perm.group_id = gid.match(/\d+/)[0];
                perm.access = 0;
                $('td input[type="checkbox"]', $tr).not('.perm-checkbox-all').each(function() {
                    var $this = $(this);
                    if ($this.is(':checked')) {
                        perm.access += $this.data('perm');
                    }
                });
                return perm;
            },
            permissions: function() {
                var deleteClick, selectAll;
                deleteClick = function() {
                    if (w.confirm("Are you sure?")) {
                        var permissions = [],
                            $tr = $(this).closest('tr').addClass("perm-delete"),
                            postData;
                        permissions.push(coreEngine.page_properties.getPerms($tr));
                        postData = "pid=" + coreEngine.pageID.match(/\d+/)[0];
                        postData += "&data=" + JSON.stringify(permissions);
                        coreEngine.ajax("server/permdelete/" + (new Date()).getTime(), postData, coreEngine.page_properties.deleteCallback, 'json');
                    }
                    return false;
                };
                selectAll = function() {
                    $(this).parent().parent().find('input[type="checkbox"]').prop('checked', $(this).is(":checked"));
                };
                $('#propPermissions').on('click', '.perm-checkbox-all', selectAll);
                $("#btnAddPerm").css("cursor", "pointer").click(function() {
                    var $tr = $("<tr>"),
                        $input = $("<input>"),
                        $first_tr;
                    $input.autocomplete({
                        minLength: 2,
                        source: function(request, response) {
                            coreEngine.getJSON("who/groups/q/" + request.term, "", response);
                        },
                        select: function(event, ui) {
                            var permissions = [],
                                postData, $deleteButton;
                            $(this).parent().append(ui.item.value).attr("id", "gid-" + ui.item.id);
                            permissions.push(coreEngine.page_properties.getPerms($(this).parent().parent()));
                            postData = "pid=" + coreEngine.pageID.match(/\d+/)[0];
                            postData += "&data=" + JSON.stringify(permissions);
                            coreEngine.ajax("server/permadd/" + (new Date()).getTime(), postData, coreEngine.genericCallBack, 'json');
                            $deleteButton = $("<a>").addClass("perm-btn-delete delete-a").css("cursor", "pointer").click(deleteClick);
                            $(this).parent().parent().find("td:last-child").append($deleteButton);
                            $(this).remove();
                        }
                    });
                    $("<th>", {
                        "scope": "row"
                    }).append($input).appendTo($tr);
                    $first_tr = $("#propPermissions tr:first");
                    $('td input[type="checkbox"]', $first_tr).each(function() {
                        var $this = $(this),
                            $td = $('<td>').appendTo($tr);
                        $('<input>', {
                            "class": $this.attr("class"),
                            "type": "checkbox"
                        }).data('perm', $this.data('perm')).appendTo($td);
                    });
                    $('td:first input[type="checkbox"]', $tr).prop('checked', true);
                    $('<td>', {
                        "class": "actions"
                    }).appendTo($tr);
                    $("#propPermissions").append($tr);
                    $input.focus();
                });
                $(".perm-btn-delete").css("cursor", "pointer").click(deleteClick);
            },
            revisions: function() {
                $(".revs-btn-delete").css("cursor", "pointer").click(function() {
                    if (w.confirm("Are you sure?")) {
                        var revID = $(this).parent().parent().addClass("perm-delete").find("td:first-child").text(),
                            postData;
                        postData = "pid=" + coreEngine.pageID;
                        postData += "&revid=" + revID;
                        coreEngine.ajax("server/revdelete/" + (new Date()).getTime(), postData, coreEngine.page_properties.deleteCallback, 'json');
                    }
                });
                $(".revs-btn-revert").css("cursor", "pointer").click(function() {
                    if (w.confirm("Are you sure?")) {
                        var revID = $(this).parent().parent().find("td:first-child").text(),
                            postData;
                        postData = "pid=" + coreEngine.pageID;
                        postData += "&revid=" + revID;
                        coreEngine.ajax("server/revrev/" + (new Date()).getTime(), postData, coreEngine.page_properties.revrevCallback, 'json');
                    }
                });
                $(".revs-btn-view").css("cursor", "pointer").click(function() {
                    var revID = $(this).parent().parent().find("td:first-child").text();
                    document.location = coreEngine.siteRoot + "articles/" + coreEngine.pageID + "/revision/" + revID;
                    return false;
                });
            },
            deleteCallback: function(result) {
                if (result.isError) {
                    $.message(result.errorStr, 'error');
                } else {
                    $(".perm-delete").remove();
                }
            },
            revrevCallback: function(result) {
                if (result.isError) {
                    $.message(result.errorStr, 'error');
                } else {
                    document.location = coreEngine.siteRoot + "articles/" + coreEngine.pageID;
                }
            }
        }
    });
}(jQuery, window));