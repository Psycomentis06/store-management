export function highlightText (text: string, word: string) {
    return text.replace(word, '<strong>' + word + '</strong>')
}