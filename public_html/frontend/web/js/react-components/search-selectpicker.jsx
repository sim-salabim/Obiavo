export default class SearchSelectpicker extends React.Component {
    constructor(props) {

        super(props);
        this.state = {
            options : props.options,
        };
        
        this.props.class = this.props.attributes.className;
    }
    
    componentDidMount(){                 
        
        var $el = $(`.${this.props.class}`),
            props = {};
    
    
        $el.selectpicker({
            liveSearch: true,            
        });

        if (typeof this.props.url == undefined) return;

        $el.ajaxSelectPicker({
            ajax: {
                url: this.props.url,
                type: 'POST',
                dataType: 'json',
                data: function () {
                    var params = {
                        q: '{{{q}}}',
                        format: 'json',
                    };
                    
                    return params;
                }
            },
            locale: {
                emptyTitle: 'Search...',
                statusInitialized: false,
            },
            preprocessData: function(data){

                return this[this.props.preprocessFunc](data);          
            }.bind(this),
            preserveSelected: false,
            requestDelay: 700
        });
    }
    
    preprocessDataCity(data) {
                
        var cities = [];
    
        data.map(function(city){
            cities.push({
                'value': city.id,
                'text': city.cityText.name,
                'data': {
//                    'icon': 'icon-person',
//                    'subtext': 'Internal'
                },
                'disabled': false
            });
        })        
            
        return cities;
    }
  
    render() {           
        return (               
            <select {...this.props.attributes} data-width="100%">
                {this.state.options.map(function(option){
                    return (
                        <option value={option.value}>{option.text}</option>
                    )
                })}                                
            </select>           
        )
    }
}