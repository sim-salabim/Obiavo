export default class SearchSelectpicker extends React.Component {
    constructor(props) {

        super(props);
        this.state = {
            options : props.options,
        };            
    }
    
    componentDidMount(){        
        $('.yes')
            .selectpicker({
                liveSearch: true
        })
        .ajaxSelectPicker({
            ajax: {
                url: '/server/path/to/ajax/results',
                data: function () {
                    var params = {
                        q: '{{{q}}}'
                    };
                   console.log(params)
                    return params;
                }
            },
            locale: {
                emptyTitle: 'Search for contact...'
            },
            preprocessData: function(data){
                console.log(data)
            },
            preserveSelected: false
        });
    }
    
    /**
     * Аттрибуты
     * 
     * [
     *   'class' => '',
     *   'attributes'
     *   'data-live-search' => "true"
     * ]
     */
    setOptions() {
        console.log('set topions')
    }
    
    getOptions() {
        
    }
    
    getAttributes(){
        
        var attrHtml = '';
        
        for (var attrName in this.props.attributes){            
            var attrValue = this.props.attributes[attrName];

            attrHtml += `${attrName}="${attrValue}"`;
            
            attrHtml = `${attrHtml} `;
        }
        
        console.log(attrHtml);
        
        return attrHtml.trim();
    }
  
    render() {           
        return (               
            <select {...this.props.attributes}>
                {this.state.options.map(function(option){
                    return (
                        <option>{option}</option>
                    )
                })}                                
            </select>           
        )
    }
}