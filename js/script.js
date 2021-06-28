var old_comment; /* = document.getElementById("old_comment");*/
var new_comment;/* = document.getElementById("new_comment");*/
var edit_btn;/* = document.getElementById("edit_comment");*/
var delete_btn;/* = document.getElementById("delete_comment");*/
var comment;/* = document.getElementById("new_comment_content");*/
var date;
var comment_id;

function edit_comment(id) {
  comment_id = id;
  old_comment = document.querySelector("[data-id= p" + comment_id + "]");
  new_comment = document.querySelector("[data-id= div" + comment_id + "]");
  comment = document.querySelector("[data-id= textarea" + comment_id + "]");
  edit_btn = document.querySelector("[data-id= edit_btn" + comment_id + "]");
  delete_btn = document.querySelector("[data-id= delete_btn" + comment_id + "]");
  date = document.querySelector("[data-id= date" + comment_id + "]");
  old_comment.classList.add("d-none");
  edit_btn.classList.add("d-none");
  delete_btn.classList.add("d-none");
  date.classList.add("d-none");
  new_comment.classList.remove("d-none");
  comment.value = old_comment.innerHTML;
  comment.focus();
}

function edit_cancel() {
  old_comment.classList.remove("d-none");
  edit_btn.classList.remove("d-none");
  delete_btn.classList.remove("d-none");
  date.classList.remove("d-none");
  new_comment.classList.add("d-none");
}

function edit_done() {
  old_comment.classList.remove("d-none");
  edit_btn.classList.remove("d-none");
  delete_btn.classList.remove("d-none");
  new_comment.classList.add("d-none");
  date.classList.remove("d-none");
}