const { Component } = wp.element;
import PropTypes from 'prop-types';

import Card from './views/Card';
import Form from './views/Form';
import Theme from './views/Theme';
import axios from 'axios';

class App extends Component {
	constructor(props) {
		super(props);

		this.views = ['theme', 'card', 'form'];

		this.state = {
			currentView: this.views[0],
			theme: '',
			card: '',
		};

		this.handleNavigation = this.handleNavigation.bind(this);
		this.handleSendCard = this.handleSendCard.bind(this);
	}

	handleNavigation(viewname) {
		if (!this.views.includes(viewname)) {
			return;
		}

		this.setState({
			currentView: viewname,
		});
	}

	handleSendCard() {
		// if (this.props.viewOnly) {
		// 	console.log('Not sending an email in viewOnly mode.');
		// 	return;
		// }

		const DEVELOP_URL_PREFIX = '/_WordPress/wenskaart-app';
		axios
			.post(DEVELOP_URL_PREFIX + '/wp-json/wenskaarten/send', {
				to: '400029867@st.roc.a12.nl',
				from: 'joostkersjes@live.nl',
				subject: 'This is a test...',
				message: 'Here is where we put the message of the mail.',
			})
			.then(response => {
				console.log(response.data);
			});
	}

	render() {
		const { currentView, theme, card } = this.state;

		return (
			<div className="wenskaarten-app">
				{currentView === this.views[0] && (
					<Theme
						handleNextView={theme => {
							this.setState({ theme: theme });
							this.handleNavigation('card');
						}}
					/>
				)}
				{currentView === this.views[1] && (
					<Card
						theme={theme}
						handleNextView={card => {
							this.setState({ card: card });
							this.handleNavigation('form');
						}}
						handlePreviousView={() => {
							this.setState({ theme: null });
							this.handleNavigation('theme');
						}}
					/>
				)}
				{currentView === this.views[2] && (
					<Form
						card={card}
						handlePreviousView={() => {
							this.setState({ card: null });
							this.handleNavigation('card');
						}}
						handleSendCard={this.handleSendCard}
					/>
				)}
			</div>
		);
	}
}

App.propTypes = {
	viewOnly: PropTypes.bool,
};

export default App;
