import React from "react";

const Social = () => <div className={'d-flex'}>
    <div className={'pr-1'}>
        <a href={"#"} target={'_black'} className={'d-inline-block bg-primary rounded-circle p-1'}
           style={{width: 31, height: 31}}><i className={'text-white fa fa-facebook fa-fw'}></i></a>
    </div>
    <div className={'pl-1'}>
        <a href={"#"} target={'_black'}><i className={'text-primary fa fa-instagram fa-2x'}></i></a>
    </div>
</div>
export default Social