<!-- Update post modal -->
<div class="modal fade" id="update_post" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="update_postLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="update_postLabel">Update your post</h5>
                <button id="close_update_post_modal" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit_status_form" class="m-2" method="POST" action="actions.php" enctype="multipart/form-data" name="edit_status_form">
                <textarea id="textarea_update_post" placeholder="Say What's in Your Heart?" name="content" class="form-control" rows="3"></textarea>
                <div class="mt-3 position-relative d-none">
                    <input type="file" id="update_post_imgInp" name="upload" hidden>
                    <a role="button" id="update_post_clear_imgInp" class="unselect_img">X</a>
                    <img src="" alt="" id="update_post_img_preview" width="100px" height="100px" class="p-1">
                    <div class="progress" style="width: 100px;">
                        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                            0%
                        </div>
                    </div>
                </div>
                <div class="mt-3 d-flex justify-content-between">
                    <button type="button" class="btn btn-dark" name="fileToUpload" onclick="document.getElementById('update_post_imgInp').click();"><i class="fa fa-camera"></i></button>
                    <button id="save_edited_post" type="submit" class="btn btn-dark">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<footer class="text-center text-light bg-dark fixed-bottom small">&copy; 2021 Copyright: <strong>Karam Nassar</strong></footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        // $(".navbar-nav .nav-link").on("click", function() {
        //     $(".navbar-nav").find(".active").removeClass("active");
        //     $(this).addClass("active");
        // });
        // Preview image 
        $('input[type="file"]').on('change', function() {
            function img_preview_func(input, img_previewer, clear_button) {
                const [file] = input.files;
                if (file) {
                    img_previewer.src = URL.createObjectURL(file);
                    $("#" + img_previewer.id).parent().removeClass("d-none");
                }
                $("#" + clear_button).click(function() {
                    $("#" + input.id).val("");
                    img_previewer.src = "";
                    $("#" + img_previewer.id).parent().addClass("d-none");
                    $(this).parent().siblings().find("[type = submit]").prop("disabled", false);
                });

            }
            if ($(this).attr('id') == "imgInp") {
                img_preview_func(imgInp, img_preview, "clear_imgInp");
            } else if ($(this).attr('id') == "user_image_input") {
                img_preview_func(user_image_input, profile_img_preview, "profile_clear_imgInp");
            } else if ($(this).attr('id') == "update_post_imgInp") {
                img_preview_func(update_post_imgInp, update_post_img_preview, "update_post_clear_imgInp");
            }
        });
        // search events
        $("#search_karam_space").on("click", function() {
            $("#search_input").toggleClass("d-none")
            $("#tabs").toggleClass("hide")
        });

        $("#search").keyup(function() {
            var action = "search_people"
            var query = $(this).val();
            if (query != "") {
                $.ajax({
                    url: "actions.php",
                    method: "POST",
                    data: {
                        query: query,
                        action: action
                    },
                    beforeSend: function() {
                        $('#search_results').html('<li align="center"><i class="fa fa-circle-o-notch fa-spin text-white"></i></li>');
                    },
                    success: function(data) {
                        $("#search_results").html(data);
                    }
                });
            } else {
                $("#search_results").html("");
            }

        });
        // Post events
        $(".posts").on("submit", "#update_status_form", function(e) {
            e.preventDefault();
            var formdata = new FormData(this);
            formdata.append("action", "add_post");
            $.ajax({
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = parseInt((evt.loaded / evt.total) * 100);
                            $('.progress-bar').width(percentComplete + '%');
                            $('.progress-bar').html(percentComplete + '%');
                        }
                    }, false);
                    return xhr;
                },
                url: "actions.php",
                method: "POST",
                contentType: false,
                cache: false,
                processData: false,
                data: formdata,
                beforeSend: function() {
                    $("#share_a_post").prop("disabled", true);
                },
                success: function(data) {
                    $(".posts  div:eq(0)").after(data);
                    $("#no_posts").parent().removeClass("border");
                    $("#no_posts").parent().html("");
                    $("#post_content").val("");
                    $("#clear_imgInp").click();
                    $("#share_a_post").prop("disabled", false);

                }
            });
        });


        $(".posts").on("click", ".edit_post", function() {
            $("#save_edited_post").val($(this).val());
            $("#textarea_update_post").html($("div[data-id = div" + $(this).val() + "]").html());
            var file = $("img[data-id = postImg" + $(this).val() + "]").attr("src");
            update_post_img_preview.src = file;
            if (file != "") {
                $("#update_post_img_preview").parent().removeClass("d-none");
                $("#update_post_clear_imgInp").click(function() {
                    $("#update_post_img_preview").parent().addClass("d-none");
                    update_post_img_preview.src = "";

                });
            }
        });


        $("#close_update_post_modal").on("click", function() {
            $("#update_post_img_preview").parent().addClass("d-none");

        });

        $("#update_post").on("submit", "#edit_status_form", function(e) {
            e.preventDefault();
            var formdata = new FormData(this);
            var post_id = $("#save_edited_post").val();
            formdata.append("action", "edit_post");
            formdata.append("post_id", post_id);
            formdata.append("post_img", $("#update_post_img_preview").attr("src"));
            $.ajax({
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = parseInt((evt.loaded / evt.total) * 100);
                            $('.progress-bar').width(percentComplete + '%');
                            $('.progress-bar').html(percentComplete + '%');
                        }
                    }, false);
                    return xhr;
                },
                url: "actions.php",
                method: "POST",
                contentType: false,
                cache: false,
                processData: false,
                data: formdata,
                beforeSend: function() {
                    $("#save_edited_post").prop("disabled", true);
                },
                success: function(data) {
                    var result = jQuery.parseJSON(data);
                    $("div[data-id = div" + post_id + "]").html(result[0]);
                    $("img[data-id = postImg" + post_id + "]")[0].src = result[1];
                    $("#close_update_post_modal").click();
                    $("#save_edited_post").prop("disabled", false);
                }
            });
        });

        $(".posts").on('click', ".delete_post", function() {
            var action = "delete_post";
            var post_id = $(this).val();
            var btn = $(this).parents(".post");
            $.ajax({
                url: "actions.php",
                method: "POST",
                data: {
                    action: action,
                    post_id: post_id
                },
                success: function(data) {
                    btn.html("");
                    btn.removeClass("border");
                }
            });
        });
        // like events
        $(".posts").on('click', ".like_post", function() {
            var action = "like_post";
            var btn = $(this);
            var val = $(this).val();
            $.ajax({
                url: "actions.php",
                method: "POST",
                data: {
                    action: action,
                    post_id: val
                },
                success: function(data) {
                    if (data == "like") {
                        btn.removeClass("text-secondary");
                        btn.addClass("text-primary");
                        btn.html('<span><i class="fa fa-thumbs-up"></i></span>');
                        count_post_likes(val, data, btn);
                    } else {
                        btn.removeClass("text-primary");
                        btn.addClass("text-secondary");
                        btn.html('<span><i class="fa fa-thumbs-up"></i></span>');
                        count_post_likes(val, data, btn);

                    }
                }
            });

        });

        function count_post_likes(post_id, check, btn) {
            var action = "count_post_likes";
            var btn1 = btn.parents(".post").find(".count_post_likes");
            $.ajax({
                url: "actions.php",
                method: "POST",
                data: {
                    action: action,
                    post_id: post_id
                },
                success: function(data) {
                    if (check == "like" && data != 1) {
                        btn1.html("You, and " + (data - 1) + " people liked this");
                    } else if (check == "unlike" && data != 0) {
                        btn1.html(data + " people liked this");
                    } else if (check == "like" && data == 1) {
                        btn1.html("You liked this");
                    } else {
                        btn1.html("Be the first to like this");
                    }
                }
            });
        }
        // comment events
        $(".posts").on("click", ".make_a_comment", function() {
            $(this).parents(".post").find(".add_comment_form").toggleClass("d-none");
            $(this).find("p").toggleClass("text-primary");
            $(this).find("p").toggleClass("text-secondary");

        });

        $(".posts").on("click", ".add_comment", function() {
            var action = "add_comment";
            var thisbtn = $(this).parents(".comment_section");
            comment_content = $(this).parent().siblings().find(".comment_content");
            $.ajax({
                url: "actions.php",
                method: "POST",
                data: {
                    action: action,
                    post_id: $(this).val(),
                    comment_content: comment_content.val()

                },
                success: function(data) {
                    thisbtn.append(data)
                    comment_content.val("");
                }
            });
        });
        $(".posts").on("click", "[name = edit_comment]", function() {
            $(this).parents("[name = comment_field]").find("[name = new_comment_panel]").removeClass("d-none");
            $(this).parents("[name = comment_field]").find("[name = new_comment_content]").html($(this).parents("[name = comment_field]").find("[name = old_comment]").html());
            $(this).parents("[name = comment_field]").find("[name = new_comment_content]").focus();
            $(this).parents("[name = comment_edit_delete]").addClass("d-none");
            $(this).parents("[name = comment_field]").find("[name = old_comment]").addClass("d-none");
            $(this).parents("[name = comment_field]").find("[name = date]").addClass("d-none");
        });

        $(".posts").on("click", "[name = edit_comment_cancel]", function() {
            $(this).parents("[name = comment_field]").find("[name = new_comment_panel]").addClass("d-none");
            $(this).parents("[name = comment_field]").find("[name = comment_edit_delete]").removeClass("d-none");
            $(this).parents("[name = comment_field]").find("[name = old_comment]").removeClass("d-none");
            $(this).parents("[name = comment_field]").find("[name = date]").removeClass("d-none");

        });

        $(".posts").on("click", "[name = edit_comment_done]", function() {
            var action = "edit_comment";
            var comment_id = $(this).val();
            var new_comment_content = $(this).siblings("textarea");
            var edited_paragraph = $(this).parent().parent().find("[name = old_comment]");
            var btn = $(this);
            $.ajax({
                url: "actions.php",
                method: "POSt",
                data: {
                    action: action,
                    comment_id: comment_id,
                    new_comment_content: new_comment_content.val()
                },
                success: function(data) {
                    edited_paragraph.html(data);
                    btn.parents("[name = comment_field]").find("[name = new_comment_panel]").addClass("d-none");
                    btn.parents("[name = comment_field]").find("[name = comment_edit_delete]").removeClass("d-none");
                    btn.parents("[name = comment_field]").find("[name = old_comment]").removeClass("d-none");
                    btn.parents("[name = comment_field]").find("[name = date]").removeClass("d-none");
                }
            });
        });
        $(".posts").on("click", "[name = delete_comment]", function() {
            console.log("error");
            var action = "delete_comment";
            var comment_id = $(this).val();
            var remove_comment = $(this).parents("[name = comment_field]");
            $.ajax({
                url: "actions.php",
                method: "POSt",
                data: {
                    action: action,
                    comment_id: comment_id,
                },
                success: function(data) {
                    remove_comment.html("");
                },
            });
        });
        // Friendship events
        var friend_request_seen = false;
        $("#friend_request_area").click(function() {
            if (!friend_request_seen) {
                load_friends_request_list();
                friend_request_seen = true;
            }
        });

        function count_un_seen_friend_request() {
            var action = 'count_un_seen_friend_request';

            $.ajax({
                url: "actions.php",
                method: "POST",
                data: {
                    action: action
                },
                success: function(data) {
                    if (data > 0) {
                        $('#unseen_friend_request_area').html('<span class="label text-danger">' + data + '</span>');
                        friend_request_seen = false;
                    }
                }
            })
        }

        var notifications_seen = false;
        $("#notifications_area").click(function() {
            if (!notifications_seen) {
                load_notifications();
                notifications_seen = true;
            }
        });

        function count_un_seen_notificatons() {
            var action = 'count_un_seen_notificatons';

            $.ajax({
                url: "actions.php",
                method: "POST",
                data: {
                    action: action
                },
                success: function(data) {
                    if (data > 0) {
                        $('#unseen_notifications_area').html('<span class="label text-danger">' + data + '</span>');
                        notifications_seen = false;
                    }
                }
            })
        }

        function load_notifications() {
            var action = "load_notifications";
            $.ajax({
                url: "actions.php",
                method: "POST",
                data: {
                    action: action
                },
                beforeSend: function() {
                    $('#notifications_list').html('<li align="center"><i class="fa fa-circle-o-notch fa-spin text-white"></i></li>');

                },
                success: function(data) {
                    $("#notifications_list").html(data);
                    remove_notifications_count();
                }

            });
        }
        function remove_notifications_count() {
            var action = "remove_notifications_count";
            $.ajax({
                url: "actions.php",
                method: "POST",
                data: {
                    action: action
                },
                success: function(data) {
                    $("#unseen_notifications_area").html("");

                }
            });
        }

        count_un_seen_friend_request();
        count_un_seen_notificatons();
        setInterval(function() {
            count_un_seen_friend_request();
            count_un_seen_notificatons();
        }, 5000);

        function load_friends_request_list() {
            var action = "load_friend_request_list";
            $.ajax({
                url: "actions.php",
                method: "POST",
                data: {
                    action: action
                },
                beforeSend: function() {
                    $('#friend_request_list').html('<li align="center"><i class="fa fa-circle-o-notch fa-spin text-white"></i></li>');

                },
                success: function(data) {
                    $("#friend_request_list").html(data);
                    remove_friend_request_count();
                }

            });
        }


        function remove_friend_request_count() {
            var action = "remove_friend_request_count";
            $.ajax({
                url: "actions.php",
                method: "POST",
                data: {
                    action: action
                },
                success: function(data) {
                    $("#unseen_friend_request_area").html("");

                }
            });
        }

        $("#add_friend").click(function() {
            var request_to_id = <?php echo $request_to_id ?>;
            var action = "add_friend";
            $.ajax({
                url: "actions.php",
                method: "POST",
                data: {
                    request_to_id: request_to_id,
                    action: action
                },
                success: function(data) {
                    location.reload();
                }
            });

        });
        $("#cancel_request, #reject_friend,#remove_friend").click(function() {
            var request_id = <?php echo $friend_request_id ?>;
            var action = "cancel_request";

            $.ajax({
                url: "actions.php",
                method: "POST",
                data: {
                    request_id: request_id,
                    action: action
                },
                success: function(data) {
                    location.reload();
                }
            });
        });
        $("#accept_friend").click(function() {
            var request_id = <?php echo $friend_request_id ?>;
            var action = "accept_friend";
            $.ajax({
                url: "actions.php",
                method: "POST",
                data: {
                    request_id: request_id,
                    action: action
                },
                success: function(data) {
                    location.reload();
                }
            });

        });

        function load_friends(query = "") {
            var action = "load_friend_list";
            $.ajax({
                url: "actions.php",
                method: "POST",
                data: {
                    query: query,
                    action: action,
                    user_id: <?php echo $request_to_id ?>
                },
                success: function(data) {
                    $("#friend_list").html(data);
                }
            });
        }
        $("#search_friend_list").keyup(function() {
            var val = $(this).val();
            if (val != "") {
                load_friends(val);
            } else {
                load_friends();
            }
        });
        load_friends();

        $("#search_friend_list").keyup(function() {
            var val = $(this).val();
            if (val != "") {
                load_friends(val);
            } else {
                load_friends();
            }
        });
        // Edit personal info
        $("#edit_personal_info").on("click", function() {
            $(this).addClass("d-none");
            $("#show_personal_info").addClass("d-none");
            $("#edit_personal_info_form").removeClass("d-none");
            $("#input_firstname").val($("#firstname").html());
            $("#input_lastname").val($("#lastname").html());
            $("#input_email").val($("#email").html());

            if ($("#gender").html().toLowerCase() == "male") {
                $("#male").prop("checked", true);
            } else if ($("#gender").html().toLowerCase() == "female") {
                $("#female").prop("checked", true);
            }
            var date = $("#birthday").html().split("/");
            $("[name = day").val(date[0]);
            $("[name = month").val(date[1]);
            $("[name = year").val(date[2]);
        });

        $("#cancel_edit_personal_info").on("click", function() {
            $("#edit_personal_info").removeClass("d-none");
            $("#show_personal_info").removeClass("d-none");
            $("#edit_personal_info_form").addClass("d-none");
        });

        $("#personal_info").on("submit", "#edit_personal_info_form", function(e) {
            e.preventDefault();
            var formdata = new FormData(this);
            formdata.append("action", "edit_presonal_info");
            $.ajax({
                url: "actions.php",
                method: "POST",
                data: formdata,
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    $("#show_personal_info").html(data);
                    $("#edit_personal_info").removeClass("d-none");
                    $("#show_personal_info").removeClass("d-none");
                    $("#edit_personal_info_form").addClass("d-none");
                }
            });
        });

    }); //Document ready
</script>
</body>

</html>