export function truncateString(str, maxLength = 10) {
    if (str.length <= maxLength) {
        return str;
    }
    return str.slice(0, maxLength) + '...';
}

export function numberFormat(number, decimals = 4) {
    return parseFloat(number.toFixed(decimals));
}
