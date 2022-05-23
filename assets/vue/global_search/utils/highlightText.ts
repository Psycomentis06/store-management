export default function (text: string, word: string) {
    return text.replace(word, '<strong>' + word + '</strong>')
}