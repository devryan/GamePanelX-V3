/*
*
* Misc Functions
*
*/

// Information Box display (msg optional)
function infobox(type,msg)
{
    // Success
    if(type == 's')
    {
        $('.infobox').hide().html('<img src="images/icons/medium/success.png" border="0" /><br />').fadeIn();
        var defmsg = 'Success!';
    }
    
    // Failure
    else if(type == 'f')
    {
        $('.infobox').hide().html('<img src="images/icons/medium/error.png" border="0" /><br />').fadeIn();
        var defmsg = '<b>Failure</b>!';
    }
    
    // Status Info
    else if(type == 'i')
    {
        $('.infobox').hide().html('<img src="images/icons/medium/info.png" border="0" width="32" height="32" /><br />').fadeIn();
        var defmsg = 'Please wait ...';
    }
    
    
    // Optionally append message
    if(msg) $('.infobox').append(msg);
    else $('.infobox').append(defmsg);
}

/* ****************************************************************** */

/*
*
* Load Main pages in /
*
*/

function mainpage(page,type,urlappend)
{
    // OK Pages
    var pages = ['servers','settings','userperms'];
    
    $.each(pages,function(key,value){
        if(page == value)
        {
            okPage = '1';
            return false;
        }
    });
    
    if(okPage != '1')
    {
        alert("Invalid Page: "+page);
        return false;
    }
    
    // Servers
    if(page == 'servers' && type != "")
    {
        if(type == "g") var addurl = '&t=g';
        else if(type == "v") var addurl = '&t=v';
        else var addurl = '';
    }
    // Use type as ID
    else if(type)
    {
        var addurl = '&id='+type;
    }
    else
    {
        var addurl = '';
    }
    
    if(!urlappend) var urlappend = '';

    // -----------------------------------

    // Load page
    $.ajax({
        url: 'ajax/ajax.php',
        data: 'a=main_'+page+addurl+urlappend,
        success:function(html){
            //$('#panel_center').html(html);
            //window.location.href += '#'+page;
            $('#panel_center').empty().html(html).hide().fadeIn();
        },
        error:function(a,b,c){
            //alert("Error: "+a+", b: "+b+", c: "+c);
	    console.log("Error: "+a+", b: "+b+", c: "+c);
        }
    });
}
