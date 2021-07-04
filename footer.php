<footer class="text-center text-light bg-dark fixed-bottom">&copy; 2021 Copyright: <strong>Karam Nassar</strong></footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
<script src="js/script.js"></script>
<script>
    $(document).ready(function() {
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
                        $('#search_results').html('<li align="center"><i class="fa fa-circle-o-notch fa-spin"></i></li>');

                    },
                    success: function(data) {
                        $("#search_results").html(data);
                    }
                });
            } else {
                $("#search_results").html("");
            }

        });
       

        $(".like_post").click(function() {
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
                        btn.parent().addClass("bg-light");
                        btn.html("Unlike");
                        count_post_likes(val, data, btn);


                    } else {
                        btn.parent().removeClass("bg-light");
                        btn.html('<span class="fa fa-thumbs-up" style="font-size:22px;"> Like</span>');
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
        $(".make_a_comment").click(function() {
            $(this).parents(".post").find(".add_comment_form").toggleClass("d-none");
        });
        $(".add_comment").click(function() {
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
                    thisbtn.append(data);
                    comment_content.val("");
                }
            });

        });
        $(".done_comment_edit").click(function() {
            var action = "edit_comment";
            var comment_id = $(this).val();
            var new_comment_content = $(this).siblings("textarea");
            var edited_paragraph = $(this).parent().parent().find(".old_comment");
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
                    edit_done();
                }
            });
        });
        $(".delete_comment").click(function() {
            var action = "delete_comment";
            var comment_id = $(this).val();
            var remove_comment = $(this).parents(".comment_to_remove");
            $.ajax({
                url: "actions.php",
                method: "POSt",
                data: {
                    action: action,
                    comment_id: comment_id,
                },
                success: function(data) {
                    remove_comment.html("");
                }
            });
        });
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


        count_un_seen_friend_request();
        setInterval(function() {
            count_un_seen_friend_request();
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
                    $('#friend_request_list').html('<li align="center"><i class="fa fa-circle-o-notch fa-spin"></i></li>');

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
        // profile actions
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
    }); //Document ready
</script>
</body>

</html>