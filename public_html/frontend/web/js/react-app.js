/**
 * Класс для удобного монтирования react компонента на страницу
 */

var rct = new function(){     
    
    /**
     * @params component_name -- Имя компонента соотвествует названию его файла без расширения     
     * @params params         -- Параметры для компонента 
     * @params dom_element    -- DOM элемент, куда будет монтироваться компонент
     */
    this.mount = function(component_name = '', dom_element, params = {}){
        
        this.params = params;
        
        var react_component = require(component_name).default;
        
        ReactDOM.render(
          React.createElement(react_component, this.params, null),
          dom_element
        );
        
    }
}