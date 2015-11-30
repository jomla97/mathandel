<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="stylesheets/sorting-list.css">
	</head>
	<body>
		<ul id="sorting-list-wrapper">
			<li id="sorting-list-title"><h1>Snabbnavigering</h1><img src="res/not-expanded-arrow-icon.png" alt="expand" title="expand"></li>
			<?php
				$pdo = pdo();
				foreach($pdo->query("SELECT name FROM categories") as $category){
					echo '
						<li class="category">
							<a href="index.php?page=browse&sort_by=' . $category['name'] . '"><h2>' . $category['name'] . '</h2></a>
							<img src="res/not-expanded-arrow-icon.png" alt="expand" title="expand">
							<ul>
								';

					$sort_by = $category['name'];
					foreach ($pdo->query("SELECT * FROM products WHERE category LIKE '$sort_by'") as $product) {
						echo '
							<a href="index.php?page=product&id=' . $product['id'] . '"><li class="sorting-list-product"><p>' . $product['name'] . '</p></li></a>
						';
					}

					echo '
							</ul>
						</li>';
				}
			?>
		</ul>

		<script type="text/javascript">
			/*$("#sorting-list-title").click(function(){
			    $(".category").slideToggle();
				*/
				//$(this).children('img').addClass('rotate');

				// Function from David Walsh: http://davidwalsh.name/css-animation-callback
				function whichAnimationEvent(){
				  var t,
				      el = document.createElement("fakeelement");

				  var animations = {
				    "animation"      : "animationend",
				    "OAnimation"     : "oAnimationEnd",
				    "MozAnimation"   : "animationend",
				    "WebkitAnimation": "webkitAnimationEnd"
				  }

				  for (t in animations){
				    if (el.style[t] !== undefined){
				      return animations[t];
				    }
				  }
				}

				var animationEvent = whichAnimationEvent();

				$("#sorting-list-title").click(function(){
				    $(".category").slideToggle();

					if($(this).children("img").attr("src") == "res/not-expanded-arrow-icon.png"){
						$(this).children('img').addClass('rotateOpen');
						console.log("Opening!");
					}
					else{
						$(this).children('img').addClass('rotateClose');
						console.log("Closing!");
					}

					$(this).on(animationEvent,
					function(event) {
						$(this).children('img').removeClass();
						console.log("Removing classes!");
					    if($(this).children("img").attr("src") == "res/not-expanded-arrow-icon.png"){
					    	$(this).children("img").attr("src", "res/expanded-arrow-icon.png");
							console.log("Swapping image!");
					    }
					    else if($(this).children("img").attr("src") == "res/expanded-arrow-icon.png"){
					    	$(this).children("img").attr("src", "res/not-expanded-arrow-icon.png");
							console.log("Swapping image!");
					    }
					    console.log("Done!");
					    //console logs an error but it seems to stop the animation queue buildup
					    stop().animate();
					});
				});

				$(".category").click(function(){
			    	$(this).children("ul").slideToggle();

					if($(this).children("img").attr("src") == "res/not-expanded-arrow-icon.png"){
						$(this).children('img').addClass('rotateOpen');
						console.log("Opening!");
					}
					else{
						$(this).children('img').addClass('rotateClose');
						console.log("Closing!");
					}

					$(this).on(animationEvent,
					function(event) {
						$(this).children('img').removeClass();
						console.log("Removing classes!");
					    if($(this).children("img").attr("src") == "res/not-expanded-arrow-icon.png"){
					    	$(this).children("img").attr("src", "res/expanded-arrow-icon.png");
							console.log("Swapping image!");
					    }
					    else if($(this).children("img").attr("src") == "res/expanded-arrow-icon.png"){
					    	$(this).children("img").attr("src", "res/not-expanded-arrow-icon.png");
							console.log("Swapping image!");
					    }
					    console.log("Done!");
					    //console logs an error but it seems to stop the animation queue buildup
					    stop().animate();
					});
				});

			    /*
			    if($(this).children("img").attr("src") == "res/not-expanded-arrow-icon.png"){
			    	$(this).children("img").attr("src", "res/expanded-arrow-icon.png")
			    }
			    else{
			    	$(this).children("img").attr("src", "res/not-expanded-arrow-icon.png")
			    }
			    */
			//});
			
			/*
			$(".category").click(function(){
			    $(this).children("ul").slideToggle();
			    if($(this).children("img").attr("src") == "res/not-expanded-arrow-icon.png"){
			    	$(this).children("img").attr("src", "res/expanded-arrow-icon.png")
			    }
			    else{
			    	$(this).children("img").attr("src", "res/not-expanded-arrow-icon.png")
			    }
			});
			*/

			$(".sorting-list-product").hover(function(){
			    $(".category").css("background-color", "#83d46a");
			},
			function(){
			    $(".category").css("background-color", "");
			});

			//LocalStorage to remember what you have expanded
			if(typeof(Storage) !== "undefined") {
			    // Code for localStorage/sessionStorage.
			} else {
			    // Sorry! No Web Storage support..
			}
		</script>
	</body>
</html>