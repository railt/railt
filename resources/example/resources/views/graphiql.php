<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/graphiql/0.10.2/graphiql.min.css" />
    <link rel="stylesheet" href="./railgun.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fetch/2.0.3/fetch.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react/15.5.4/react.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react/15.5.4/react-dom.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/graphiql/0.10.2/graphiql.min.js"></script>
</head>
<body>
    <div style="display: none" id="schemaValue"><?php if ($request->has('schema')) {
        echo htmlspecialchars($request->get('schema'));
    } else {
        echo '# Paste example graphql schema code here';
    } ?></div>
    <div id="graphiql">Loading...</div>
    <div id="schema" style="line-height: 20px;font-size: 14px;"></div>
    <script src="./railgun.js"></script>
</body>
</html>

