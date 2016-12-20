export default class SearchInput extends React.Component {
    constructor(props) {
        super(props);
        
        this.timer = null;
        
        this.handleChange = this.handleChange.bind(this);
    }
    
    handleChange(event) { 
        let value = event.target.value;
        
        let $delay = 500;

        clearTimeout(this.timer);

        this.timer = setTimeout(function(){
            
            let l = value.length;
            
            if (l >= 3 || l == 0) {
                this.upload(value);
            }
            
        }.bind(this), $delay);                
    }
    
    upload(value){
        
        $.ajax({
            url: this.props.url,
            dataType: 'json',
            type: 'POST',
            data: {
                search_text: value,
                format: 'json'
            },
            cache: false,
            success: function(data) {
                
                this.props.addTodoItem(data);
                
            }.bind(this),
            error: function(xhr, status, err) {                
              console.error(this.props.url, status, err.toString());
            }.bind(this)
        })                
    }
        
    render() {
      return (
          <div className="search">
              <input type="text" 
                    className="form-control" 
                    placeholder="Введите город/регион.." 
                    onChange={ this.handleChange } />
          </div>
      );
    }
}