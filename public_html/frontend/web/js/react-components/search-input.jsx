export default class Search extends React.Component {
  render() {
    return (
        <div className="search">
            <input type="text" className="form-control" placeholder="Введите город/регион.." />
        </div>
    /*
      <div className="well clearfix">
        <textarea className="form-control"></textarea>
        <br/>
        <button className="btn btn-primary pull-right">Tweet</button>
      </div>
      */
    );
  }
}