var SearchInput = require('./search-input.jsx').default;

export default class SearchRegionList extends React.Component {
    constructor(props) {

        super(props);
        this.state = {
            cities : props.cities,
        };
        
    }
    
    addTodoItem(item) {
        console.log(this)
        console.log(item)
        this.setState({cities: item});
    }
    
    loadCitiesFromServer() {
//        $.ajax({
//            url: this.props.url,
//            dataType: 'json',
//            cache: false,
//            success: function(data) {
//              this.setState({data: data});
//            }.bind(this),
//            error: function(xhr, status, err) {
//              console.error(this.props.url, status, err.toString());
//            }.bind(this)
//        })
    }
  
    render() {
        return ( 
            <div>
                <SearchInput addTodoItem={this.addTodoItem.bind(this)} url={this.props.url} />
                <div className="selectboxmenu-items js-scroll">
                    {this.state.cities.map(function(city){
                        return (
                            <span className="a-like">
                                <span><b>{city.cityText.name}</b> - {city.region.regionText.name}</span>
                            </span>
                        )
                    })}
                </div>
            </div>   
        )
    }
}