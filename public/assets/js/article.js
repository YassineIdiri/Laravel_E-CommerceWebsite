function rateStar(e){document.getElementById("rating").value=e;document.querySelectorAll(".rating-stars span").forEach((t,i)=>{i<e?t.innerHTML="<i class='bi bi-star-fill'></i>":t.innerHTML="<i class='bi bi-star'></i>"})}function activeComment(){document.getElementById("form").classList.toggle("displayForm");let e=document.getElementById("chevron");e.classList.toggle("bi-chevron-right"),e.classList.toggle("bi-chevron-down")}function deleteMessage(e,t){Swal.fire({title:"Are you sure you want to delete this comment?",icon:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes",cancelButtonText:"No"}).then(i=>{i.isConfirmed&&fetch(e,{method:"DELETE",headers:{"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content")}}).then(e=>{e.ok?document.getElementById(t).remove():console.error("LThe request has failed :",e.status)}).catch(e=>{console.error("The request has failed :",e)})})}function activeOption(e){e.target.parentElement.parentElement.parentElement.querySelector(".bouton").classList.toggle("activeC")}async function likeComment(e){let t=await (await fetch(url="/comment/like/"+e)).json();if(!0==t.success){let i=document.getElementById(`like-icon-${e}`);i.classList.toggle("bi-heart"),i.classList.toggle("bi-heart-fill");let n=document.getElementById("like"+e);n.textContent=t.like}}async function isLike(e){if(!0==(await (await fetch(url="/comment/isLike/"+e)).json()).value){let t=document.getElementById(`like-icon-${e}`);t.classList.add("bi-heart-fill")}else{let i=document.getElementById(`like-icon-${e}`);i.classList.add("bi-heart")}}AOS.init();