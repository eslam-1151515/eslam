const fs = require('fs');
const path = require('path');

function walk(dir) {
    let results = [];
    const list = fs.readdirSync(dir);
    list.forEach(file => {
        file = path.join(dir, file);
        const stat = fs.statSync(file);
        if (stat && stat.isDirectory()) {
            if (!file.includes('node_modules') && !file.includes('vendor')) {
                results = results.concat(walk(file));
            }
        } else {
            if (file.endsWith('.jsx') || file.endsWith('.js') || file.endsWith('.php') || file.endsWith('.blade.php')) {
                results.push(file);
            }
        }
    });
    return results;
}

const files = walk('resources/js').concat(walk('resources/views')).concat(walk('app'));

files.forEach(file => {
    let content = fs.readFileSync(file, 'utf8');
    let changed = false;

    // Replace ar-EG dates
    if (content.includes("'ar-EG'")) {
        content = content.replace(/'ar-EG'/g, "'en-US'");
        changed = true;
    }
    if (content.includes('"ar-EG"')) {
        content = content.replace(/"ar-EG"/g, '"en-US"');
        changed = true;
    }

    // Replace currency formatters with fractionDigits: 2 to 0
    if (content.includes('minimumFractionDigits: 2')) {
        content = content.replace(/minimumFractionDigits:\s*2/g, 'minimumFractionDigits: 0');
        changed = true;
    }
    if (content.includes('maximumFractionDigits: 2')) {
        content = content.replace(/maximumFractionDigits:\s*2/g, 'maximumFractionDigits: 0');
        changed = true;
    }
    
    // Specifically handle formatting functions in JS
    const oldCurrencyFormat = "return Number(amount).toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 }) + ' ?.?';";
    const newCurrencyFormat = "return new Intl.NumberFormat('en-US', {maximumFractionDigits: 0}).format(Math.round(amount)) + ' ?.?';";
    
    if (content.includes(oldCurrencyFormat)) {
         content = content.replace(oldCurrencyFormat, newCurrencyFormat);
         changed = true;
    }

    if (changed) {
        fs.writeFileSync(file, content, 'utf8');
        console.log('Updated: ' + file);
    }
});
