jQuery(document).ready(function($) {
	//On click on one of the boxes
	$('.thumbnail-focus-position .button').live('click', function(e) {
       	var $this = $(this);
     	$this.parent().find('label').parent().removeClass('button-primary');
		$this.addClass('button-primary');
		$this.find('input:radio').prop("checked", true).change();


    });
 
    //On click on the actual thumbnail
 	$('.attachment-details .thumbnail').live('click', function(e) {
 		var $this = $(this);
        var $that = $('.thumbnail-focus-position');
        var offset = $this.offset();
		var x = e.pageX - offset.left
        var y = e.pageY - offset.top;
 		var w = $this.width() ;
		var h = $this.height() ;
		var rel_x = ( x / w ) ;
		var rel_y = ( y / h ) ; 
 		rel_x = rel_x.toFixed(4);
		rel_y = rel_y.toFixed(4);
		var pos = 4;
		
		if( $this.find('.focus_grid').length <=0 ){
			$this.append('<div class="focus_grid hori" /><div class="focus_grid vert" />');
		}
		timeout = setTimeout(function(){
				$('.focus_grid').fadeOut().remove();
				clearTimeout(timeout);
			}, 3000);
			
		//Determine selected coord.
		if(rel_y < (1/3)){
			if(rel_x < (1/3))
				pos = 0;
			else if(rel_x >= (1/3) && rel_x < (2/3) )
				pos = 1;
			else if(rel_x >= (2/3))
				pos = 2;
		}else if(rel_y >= (1/3) && rel_y < (2/3)){
			if(rel_x < (1/3))
				pos = 3;
			else if(rel_x >= (1/3) && rel_x < (2/3) )
				pos = 4;
			else if(rel_x >= (2/3))
				pos = 5;
		}else if(rel_y >= (2/3)){
			if(rel_x < (1/3))
				pos = 6;
			else if(rel_x >= (1/3) && rel_x < (2/3) )
				pos = 7;
			else if(rel_x >= (2/3))
				pos = 8;
		}
		//Update boxes value
		$that.find('input').each(function(){
			if( $(this).val() == pos )
				$(this).parent().trigger("click");
		});
		
    	return false;
    });

});
