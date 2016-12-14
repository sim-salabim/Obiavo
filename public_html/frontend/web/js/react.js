var rct = new function (){
    
    /**
     * @param {text} component_name -- React component nanme
     * @param {text} dom_element    -- Mount DOM element
     * @param {array} params
     * @return ReactComponent
     */
    this.mount = function(component_name, dom_element, params) {
        
        ReactDOM.render(
            React.createElement('search', params, null),
            dom_element
        );
    }
}
