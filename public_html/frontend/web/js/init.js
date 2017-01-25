Init = new function(){       
    
    this.run = function() {        
        
        this.h1Title();
    }
    
    this.h1Title = function(){
        var h1Title = $('body > h1:first-child > span');
        
        var content = h1Title.html();
//        word_array = h1Title.html().split(/[\s\!,\.\?]+/g); // split on spaces
//        last_word = word_array.pop();             // pop the last word
//        first_part = word_array.join(' ');        // rejoin the first words together

        h1Title.html([' <a class="a-like loadcontent" data-link="/modal/cities">', content, '<i class="fa fa-caret-down"></i></a>'].join(''));
    }
    
    this.modal = function(){
        $('.modal-window')
    }
}