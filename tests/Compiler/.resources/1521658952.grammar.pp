%token T_TYPE                   type\b
%token T_NAME                   ([_A-Za-z][_0-9A-Za-z]+)

%skip T_WHITESPACE              (\xfe\xff|\x20|\x09|\x0a|\x0d)+
%skip T_COMMENT                 #[^\n]*

#HellOrWorld:
    <T_TYPE>*
