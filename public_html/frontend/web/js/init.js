Init = new function(){       
    
    this.run = function() {        
        
        this.h1Title();
    }
    
    this.h1Title = function(){
        var h1Title = $('body h1:first-child');

        word_array = h1Title.html().split(/[\s\!,\.\?]+/g); // split on spaces
        last_word = word_array.pop();             // pop the last word
        first_part = word_array.join(' ');        // rejoin the first words together

        h1Title.html([first_part, ' <a class="a-like loadcontent" data-link="/modal/cities">', last_word, '<i class="fa fa-caret-down"></i></a>'].join(''));
    }
    
    this.modal = function(){
        $('.modal-window')
    }
}