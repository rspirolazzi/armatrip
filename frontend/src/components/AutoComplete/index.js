import React, {Component, Fragment} from "react"
import PropTypes from "prop-types"
import {Form} from "react-bootstrap"

class Autocomplete extends Component {
    static propTypes = {
        suggestion: PropTypes.any,
        suggestions: PropTypes.array.isRequired,
        value: PropTypes.any
    }

    static defaultProps = {
        suggestions: []
    }

    componentWillUpdate(nextProps, nextState) {
        try {
            if (nextProps.suggestions.length > 0 && nextProps.value !== '' && this.state.userInput === '' && nextProps.value !== null) {
                this.setState({userInput: nextProps.suggestions.find(v => v.code === nextProps.value).name})
            }
        } catch (e) {
        }
    }


    constructor(props) {
        super(props)

        this.state = {
            // The active selection's index
            activeSuggestion: 0,
            // The suggestions that match the user's input
            filteredSuggestions: props.suggestions,
            // Whether or not the suggestion list is shown
            showSuggestions: false,
            // What the user has entered
            userInput: ""
        }
    }

    _isValidSuggest = value => {
        const {suggestions} = this.props
        return value && suggestions.find(suggestion => suggestion.name.toLowerCase() === value.toLowerCase()) !== null
    }

    _filterSuggestions = userInput => suggestion => ((suggestion.name.toLowerCase().indexOf(userInput.toLowerCase()) > -1) || ((suggestion.tags || '').toLowerCase().indexOf(userInput.toLowerCase()) > -1))

    // Event fired when the input value is changed
    onChangeForSuggest = e => {

        const {suggestions} = this.props
        const userInput = e.currentTarget.value

        // Filter our suggestions that don't contain the user's input
        const filteredSuggestions = suggestions.filter(this._filterSuggestions(userInput))

        // Update the user input and filtered suggestions, reset the active
        // suggestion and make sure the suggestions are shown
        this.setState({
            activeSuggestion: 0,
            filteredSuggestions,
            showSuggestions: true,
            userInput: e.currentTarget.value
        })

    }


    // Event fired when the input value is changed
    onBlurForSuggest = e => {
        const {_isValidSuggest} = this
        const {isValidSuggest} = this.props
        isValidSuggest(_isValidSuggest(this.state.userInput))
    }

    onFocusForSuggest = e => {
        let {filteredSuggestions, userInput} = this.state
        const {suggestions} = this.props
        if (userInput.length === 0) {
            filteredSuggestions = suggestions
        }
        this.setState({showSuggestions: true, filteredSuggestions: filteredSuggestions})
    }

    // Event fired when the user clicks on a suggestion
    onClick = (e, suggestion) => {
        this.countryDisable(suggestion.name)
        const {onSelectSuggest} = this.props
        // Update the user input and reset the rest of the state
        this.setState({
            activeSuggestion: 0,
            filteredSuggestions: [],
            showSuggestions: false,
            userInput: e.currentTarget.innerText
        }, () => onSelectSuggest(suggestion))
    }

    // Event fired when the user presses a key down
    onKeyDown = e => {
        const {onSelectSuggest} = this.props
        const {activeSuggestion, filteredSuggestions} = this.state

        //console.log('KEY', e.keyCode)

        // User pressed the enter key, update the input and close the
        // suggestions
        if ((e.keyCode === 13 || e.keyCode === 9) && filteredSuggestions.length > 0) {
            this.countryDisable(filteredSuggestions[activeSuggestion].name)
            this.setState({
                activeSuggestion: 0,
                showSuggestions: false,
                userInput: filteredSuggestions[activeSuggestion].name
            }, () => onSelectSuggest(filteredSuggestions[activeSuggestion]))
        }
        // User pressed the up arrow, decrement the index
        else if (e.keyCode === 38) {
            if (activeSuggestion === 0) {
                return
            }
            this.setState({activeSuggestion: activeSuggestion - 1})
        }
        // User pressed the down arrow, increment the index
        else if (e.keyCode === 40) {
            if (activeSuggestion - 1 === filteredSuggestions.length) {
                return
            }
            this.setState({activeSuggestion: activeSuggestion + 1})
        }
    }


    countryDisable(suggestion) {
        var aux_sug = suggestion.toUpperCase()
        /*if (aux_sug === "CHILE" || aux_sug === "PARAGUAY" || aux_sug === "URUGUAY" || aux_sug === "BOLIVIA" || aux_sug === "BRASIL") {
            this.props.history.push('/register/contact')
        }*/
    }

    render() {
        const {
            onChangeForSuggest,
            onBlurForSuggest,
            onFocusForSuggest,
            onClick,
            onKeyDown,
            value
        } = this

        const {
            activeSuggestion,
            filteredSuggestions,
            showSuggestions,
            userInput
        } = this.state

        const {
            onChange,
            onSelectSuggest,
            isValidSuggest,
            suggestions,
            ...rest
        } = this.props

        let suggestionsListComponent

        if (showSuggestions) {
            if (filteredSuggestions.length) {
                suggestionsListComponent = (
                    <ul className="list-group" style={{position: 'absolute', zIndex: 9999, width: '96%'}}>
                        {filteredSuggestions.map((suggestion, index) => {
                            let className = 'list-group-item'

                            // Flag the active suggestion with a class
                            if (index === activeSuggestion) {
                                className = "list-group-item active"
                            }

                            return (
                                <li
                                    className={className}
                                    key={suggestion.code}
                                    onClick={(e) => onClick(e, suggestion)}
                                >
                                    {suggestion.name}
                                </li>
                            )
                        })}
                    </ul>
                )
            }
        }

        return (
            <Fragment>
                <Form.Control
                    {...rest}
                    autoComplete={"new-password"}
                    onChange={onChangeForSuggest}
                    onBlur={onBlurForSuggest}
                    onFocus={onFocusForSuggest}
                    onKeyDown={onKeyDown}
                    value={userInput}
                />
                {suggestionsListComponent}
            </Fragment>
        )
    }
}

export default Autocomplete;