import React from 'react'
import {Col, Image, Media, Row} from 'react-bootstrap'
import {bindActionCreators} from "redux"
import {_setPackage} from "../../store/package"
import connect from "react-redux/es/connect/connect"
import {isMobile, truncateString} from "../../common/utils";
import calendar from '../../layouts/IndividualTourism/img/calender.png'
import _ from 'lodash'

const GridPackages = ({
                          packages = [],
                          _setPackage,
                          route
                      }) => {
    let packagesWithDates = packages
        .map(p => {
            return {
                ...p,
                dateRanges: _.sortBy(p.dateRanges.filter(v => v.date_start != null && v.date_end != null), (o) => {
                    return o.date_start.toDate()
                })
            }
        })
        .filter(p => p.dateRanges.length > 0)
    packagesWithDates = _.sortBy(packagesWithDates, (o) => o.dateRanges[0].date_start.toDate())


    return <Row className="list-packages">
        {packagesWithDates.filter(p => p.dateRanges.filter(v => v.date_start && v.date_end).length > 0).map(_package => {

            let image = _package.media.background_image;
            if (isMobile() && _package.media.background_image_mobile) {
                image = _package.media.background_image_mobile;
            }
            const onClick = (_package) => (e) => {
                e.preventDefault()
                _setPackage(_package)
                route('packageDetail', [_package.code])
            }
            return _.sortBy(_package.dateRanges, 'date_start').map((dateRange, index) => {
                return <Col xs={12} sm={6}>
                    <Media title={_package.name} as={'a'} onClick={onClick({..._package, date_range_index: index})}
                           key={_package.code + index}
                           className={'rounded-lg rounded-dashed border-primary mb-2'}>
                        <img
                            width={84}
                            height={84}
                            className="media-img mr-3 rounded-lg"
                            src={image}
                            alt={_package.name}
                        />
                        <Media.Body className={'text-left'}>
                            <h5 className={'m-0 text-uppercase'}>{truncateString(_package.name, isMobile() ? 23 : 30)}</h5>
                            <small
                                className={'m-0 text-info text-uppercase'}>{truncateString(dateRange.name, isMobile() ? 30 : 50)}</small>
                            <div className={'d-flex'}>
                                <Image height={24} src={calendar} className={'mr-2 mt-1 rounded-lg'}/>
                                <div className={'d-flex flex-column'}>
                                    <small className={'m-0'}>Salida desde Bs.As.</small>
                                    <small className={'m-0'}>{dateRange.date_start.format('DD/MM/YYYY')}</small>
                                </div>
                            </div>

                        </Media.Body>
                    </Media>
                </Col>
            })
        })}
    </Row>
}


const mapStateToProps = state => ({})

const mapDispatchToProps = dispatch => bindActionCreators({
    _setPackage: _setPackage,
}, dispatch)

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(GridPackages)
