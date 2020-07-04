class log {
    static consoleLog = true;

    static error (content=null) {
        if (this.consoleLog === true && content !== null) {
            console.error(content);
        }
    }
}

export default log;