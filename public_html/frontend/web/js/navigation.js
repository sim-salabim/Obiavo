var Navigation = new function(){
    
    this.host = window.location.hostname;    
    this.path = window.location.pathname;
    this.protocol = window.location.protocol;
    this.urlParams = window.location.search;
    
    this.changeLocation = function(url){
        
        if (url){            
                
            this.to(url);            
        }
    }
    
    this.to = function(url){
        window.location.href = url;
    }
}

