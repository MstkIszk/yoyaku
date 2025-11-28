    // IFRAME 内で動いている場合は CSS:head_frame を非表示とする --

    // window.self === window.top でない場合、そのページは iframe 内で動作している
    if (window.self !== window.top) {
        // iframe 内で動作している場合、head_frameを非表示にするスタイルを動的に追加

        // 1. スタイル要素を作成
        const style = document.createElement('style');
        style.type = 'text/css';

        // 2. 非表示のCSSルールを定義
        // !important を使用して、既存のスタイルを上書きして強制的に非表示にする
        const css = `
            .head_frame {
                display: none !important;
            }
        `;

        // 3. スタイルをDOMに追加
        if (style.styleSheet) {
            // IE対応 (古いブラウザ向け)
            style.styleSheet.cssText = css;
        } else {
            // 標準的なブラウザ向け
            style.appendChild(document.createTextNode(css));
        }

        // <head> 要素の最後にスタイルを追加
        document.head.appendChild(style);

        console.log('Detected running inside an iframe. .head_frame is hidden.');
    }
