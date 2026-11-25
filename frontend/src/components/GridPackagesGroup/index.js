import React from 'react'
import {Card, Col, Row} from 'react-bootstrap'
import {bindActionCreators} from "redux"
import {_setPackageGroup} from "../../store/package"
import connect from "react-redux/es/connect/connect"

const GridPackagesGroup = ({
                               packages_grouped,
                               _setPackageGroup,
                               route,
                           }) => {


    let keys_groups = Object.keys(packages_grouped).filter(k => packages_grouped[k].filter(p => p.dateRanges.filter(v => v.date_start && v.date_end).length > 0).length > 0)
    return <Row className={'list-packages-group mx-1 justify-content-sm-center'}>
        {keys_groups.map(group_key => {
            const group = packages_grouped[group_key][0].package_group;

            let image = group.media.background_image;

            const onClick = (e) => {
                e.preventDefault()
                _setPackageGroup(group)
                route('selectPackages', [group.code])
            }

            return <Col xs={6} sm={3} key={group_key} className={'p-1'}>
                <a href={'#'} onClick={onClick}><Card bg={'primary'} className={'rounded-lg border-0'}>
                    <Card.Img className={'img-fluid rounded-lg'} variant="top" src={image}/>
                    <Card.Body className={'text-white text-uppercase p-0'}>
                        <Card.Title as={'h5'} className={'mt-2'}>{group.name}</Card.Title>
                    </Card.Body>
                </Card></a>
            </Col>
        })}
    </Row>
}


const mapDispatchToProps = dispatch => bindActionCreators({
    _setPackageGroup: _setPackageGroup,
}, dispatch)

export default connect(
    null,
    mapDispatchToProps
)(GridPackagesGroup)
