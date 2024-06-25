jQuery(document).ready(function($){
    update_leaderboard();
    //update the leaderboard every 5 seconds
    var intervalId = window.setInterval(function(){
        update_leaderboard();
    }, 5000);

});

function update_leaderboard(){
    //fill all cells of the leaderboard with up to date info from database
    jQuery.ajax({
        type:"POST",
        dataType:"json",
        url: ajax_object.ajaxurl,
        data:{
            action: 'get_leaderboard_info',//call this in php file
        },
        success:function(leaderboardInfo){//response is what was echoed from action: function above
            var users = leaderboardInfo[0];
            var userClicks = leaderboardInfo[1];
            var numUsers = users.length;
            for (let row=1; row<6; row++){
                var thisUser = users[row-1];
                var thisUserClicks = userClicks[row-1];
                if (row<=numUsers && thisUserClicks>0){
                    jQuery("#fun-button-leaderboard :nth-child(1) :nth-child(" + (row+1).toString() + ") :nth-child(2)").html(thisUser);
                    jQuery("#fun-button-leaderboard :nth-child(1) :nth-child(" + (row+1).toString() + ") :nth-child(3)").html(thisUserClicks);
                }else{
                    jQuery("#fun-button-leaderboard :nth-child(1) :nth-child(" + (row+1).toString() + ") :nth-child(2)").html("");
                    jQuery("#fun-button-leaderboard :nth-child(1) :nth-child(" + (row+1).toString() + ") :nth-child(3)").html("");
                }
            }
        }
    });
}
