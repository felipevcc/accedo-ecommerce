// Format numbers to Colombian peso
export function formatCurrency(value) {
	return new Intl.NumberFormat('es-CO', {style: 'currency', currency: 'COP', maximumFractionDigits: 0 }).format(value);
}

// Pluralize words based on a given amount
export function pluralize(word, amount) {
	return amount > 1 ? `${word}s` : word;
}
