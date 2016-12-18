export default class SearchInput extends React.Component {
    constructor(props) {
        super(props);
        
        this.handleChange = this.handleChange.bind(this);
    }
    
    handleChange(event) { 
        var value = event.target.value;
        
        if (value.length > 3) {
            this.upload(value);
        }
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