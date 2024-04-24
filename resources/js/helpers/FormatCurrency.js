// Format numbers to Colombian peso
export default function formatCurrency(value) {
	return new Intl.NumberFormat('es-CO', {style: 'currency', currency: 'COP', maximumFractionDigits: 0 }).format(value);
}
